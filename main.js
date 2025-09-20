document.addEventListener('DOMContentLoaded', () => {
  loadPrograms();
  loadYears();
  loadSemesters();
  loadStudents();

  const form = document.getElementById('studentForm');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const studentId = document.getElementById('studentId').value;
    const name = document.getElementById('studentName').value.trim();
    const program = document.getElementById('programSelect').value;
    const year = document.getElementById('yearSelect').value;
    const semester = document.getElementById('semesterSelect').value;
    const allowance = document.getElementById('allowance').value;

    if (!name || !program || !year || !semester || !allowance) {
      alert('Please fill in all fields');
      return;
    }

    const payload = { name, program, year, semester, allowance };

    try {
      let response;
      if (studentId) {
        payload.id = studentId;
        response = await fetch('api/updateStudent.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      } else {
        response = await fetch('api/addStudent.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      }
      const result = await response.json();
      if (result.success) {
        alert(result.message);
        form.reset();
        document.getElementById('studentId').value = '';
        loadStudents();
      } else {
        alert('Error: ' + result.message);
      }
    } catch (error) {
      alert('Network error');
      console.error(error);
    }
  });
});

async function loadPrograms() {
  try {
    const res = await fetch('api/getPrograms.php');
    const data = await res.json();
    const select = document.getElementById('programSelect');
    data.data.forEach(prog => {
      const option = document.createElement('option');
      option.value = prog.id;
      option.textContent = prog.name;
      select.appendChild(option);
    });
  } catch (e) {
    console.error('Failed to load programs', e);
  }
}

async function loadYears() {
  try {
    const res = await fetch('api/getYears.php');
    const data = await res.json();
    const select = document.getElementById('yearSelect');
    data.data.forEach(year => {
      const option = document.createElement('option');
      option.value = year.id;
      option.textContent = year.school_year;
      select.appendChild(option);
    });
  } catch (e) {
    console.error('Failed to load years', e);
  }
}

async function loadSemesters() {
  try {
    const res = await fetch('api/getSemesters.php');
    const data = await res.json();
    const select = document.getElementById('semesterSelect');
    data.data.forEach(sem => {
      const option = document.createElement('option');
      option.value = sem.id;
      option.textContent = sem.semester_name;
      select.appendChild(option);
    });
  } catch (e) {
    console.error('Failed to load semesters', e);
  }
}

async function loadStudents() {
  try {
    const res = await fetch('api/getStudents.php');
    const data = await res.json();
    const tbody = document.querySelector('#studentsTable tbody');
    tbody.innerHTML = '';

    data.data.forEach(student => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${student.id}</td>
        <td>${student.name}</td>
        <td>${student.program_name}</td>
        <td>${student.year}</td>
        <td>${student.semester}</td>
        <td>${student.subjects || '-'}</td>
        <td>${student.allowance}</td>
        <td>
          <button onclick="editStudent(${student.id})">Edit</button>
          <button onclick="deleteStudent(${student.id})">Delete</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  } catch (e) {
    console.error('Failed to load students', e);
  }
}

async function editStudent(id) {
  try {
    const res = await fetch('api/getStudentById.php?id=' + id);
    const result = await res.json();

    if (result.success && result.data) {
      const student = result.data;
      document.getElementById('studentId').value = student.id;
      document.getElementById('studentName').value = student.name;
      document.getElementById('programSelect').value = student.program_id;
      document.getElementById('yearSelect').value = student.year_id;
      document.getElementById('semesterSelect').value = student.semester_id;
      document.getElementById('allowance').value = student.allowance;
      document.getElementById('formSubmit').textContent = 'Update Student';
    } else {
      alert('Failed to load student data');
    }
  } catch (e) {
    console.error(e);
    alert('Error fetching student data');
  }
}


async function deleteStudent(id) {
  if (!confirm('Are you sure you want to delete this student?')) return;

  try {
    const res = await fetch('api/deleteStudent.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({id})
    });
    const result = await res.json();
    if (result.success) {
      alert(result.message);
      loadStudents();
    } else {
      alert('Delete failed: ' + result.message);
    }
  } catch (e) {
    console.error(e);
    alert('Error deleting student');
  }
}
