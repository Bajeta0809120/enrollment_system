document.addEventListener('DOMContentLoaded', () => {
  loadStudents();
  loadSubjects();
  loadEnrollments();

  document.getElementById('enrollForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const student_id = document.getElementById('studentSelect').value;
    const subject_id = document.getElementById('subjectSelect').value;

    if (!student_id || !subject_id) return alert("Please select both student and subject.");

    const res = await fetch('api/enrollStudent.php', {
      method: 'POST',
      body: JSON.stringify({ student_id, subject_id })
    });
    const data = await res.json();
    alert(data.message);
    if (data.success) loadEnrollments();
  });
});

async function loadStudents() {
  const res = await fetch('api/getStudents.php');
  const { data } = await res.json();
  const studentSelect = document.getElementById('studentSelect');
  studentSelect.innerHTML = '<option value="">-- Select Student --</option>';
  data.forEach(s => {
    const opt = document.createElement('option');
    opt.value = s.id;
    opt.textContent = `${s.name}`;
    studentSelect.appendChild(opt);
  });
}

async function loadSubjects() {
  const res = await fetch('api/getSubjects.php');
  const { data } = await res.json();
  const subjectSelect = document.getElementById('subjectSelect');
  subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';
  data.forEach(s => {
    const opt = document.createElement('option');
    opt.value = s.id;
    opt.textContent = `${s.subject_name} (${s.semester_name} - ${s.year_name})`;
    subjectSelect.appendChild(opt);
  });
}

async function loadEnrollments() {
  const res = await fetch('api/getEnrollments.php');
  const { data } = await res.json();
  const tbody = document.getElementById('enrollmentTableBody');
  tbody.innerHTML = '';

  data.forEach(e => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${e.student_name}</td>
      <td>${e.subject_name}</td>
      <td>${e.program_name}</td>
      <td>${e.year_name}</td>
      <td>${e.semester_name}</td>
      <td>
        <button onclick="removeEnrollment(${e.id})">Remove</button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

async function removeEnrollment(id) {
  if (!confirm("Are you sure you want to remove this enrollment?")) return;

  const res = await fetch('api/removeEnrollment.php', {
    method: 'POST',
    body: JSON.stringify({ enrollment_id: id })
  });
  const data = await res.json();
  alert(data.message);
  if (data.success) loadEnrollments();
}
