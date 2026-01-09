<!DOCTYPE html>
<html>
<head>
    <title>Setup Audit</title>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        ul#auditor-suggestions { padding: 0; margin: 5px 0; border: 1px solid #ddd; max-height: 150px; overflow-y: auto; }
        ul#auditor-suggestions li { list-style: none; padding: 8px; cursor: pointer; background: #fff; border-bottom: 1px solid #eee; }
        ul#auditor-suggestions li:hover { background: #f0f9ff; }
        .responder-box { background: #f9fafb; padding: 15px; border: 1px solid #e5e7eb; border-radius: 6px; margin-bottom: 10px; }
        label { font-weight: bold; font-size: 14px; display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { cursor: pointer; }
    </style>
</head>
<body>

<h2>Identitas Auditor & Responder</h2>

<form method="POST" action="{{ route('audit.start') }}">
    @csrf

    <div style="background: #eff6ff; padding: 20px; border-radius: 8px; border: 1px solid #bfdbfe;">
        <h4 style="margin-top:0; color: #1e40af;">Data Auditor</h4>
        <label>Nama Auditor</label>
        <div style="position: relative;">
            <input type="text" id="auditor_name" name="auditor_name" autocomplete="off" required placeholder="Ketik nama...">
            <ul id="auditor-suggestions"></ul>
        </div>
        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>NIK Auditor</label>
                <input type="text" id="auditor_nik" name="auditor_nik">
            </div>
            <div style="flex: 1;">
                <label>Departemen Auditor</label>
                <input type="text" id="auditor_department" name="auditor_department">
            </div>
        </div>
    </div>
    <br>

    <div style="background: #fdf2f8; padding: 20px; border-radius: 8px; border: 1px solid #fbcfe8;">
        <h4 style="margin-top:0; color: #9d174d;">Responder (Opsional)</h4>
        <div id="responders"></div>
        <button type="button" onclick="addResponder()" style="padding: 6px 12px; border: 1px solid #ccc; background: white; border-radius: 4px;">+ Tambah Responder</button>
    </div>
    <br>

    <div style="background: #f0fdf4; padding: 20px; border-radius: 8px; border: 1px solid #bbf7d0;">
        <h4 style="margin-top:0; color: #166534;">Informasi Audit</h4>
        
        <label>Departemen yang Diaudit</label>
        <select name="department_id" required>
            <option value="">-- Pilih Departemen --</option>
            {{-- Bagian ini yang sebelumnya error, sekarang aman karena Controller sudah benar --}}
            @if(isset($departments))
                @foreach ($departments as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            @endif
        </select>

        <label>Tanggal Audit</label>
        <input type="date" name="audit_date" value="{{ date('Y-m-d') }}" required>
    </div>
    <br><br>

    <button type="submit" style="width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: bold;">Mulai Audit</button>
</form>

<script>
/* SCRIPT JS SAMA SEPERTI SEBELUMNYA */
const auditorInput = document.getElementById('auditor_name');
const suggestionBox = document.getElementById('auditor-suggestions');

auditorInput.addEventListener('input', async function () {
    const q = this.value;
    suggestionBox.innerHTML = '';
    if (q.length < 2) return;
    try {
        const res = await fetch('/api/auditors/search?q=' + q);
        if(res.ok) {
            const data = await res.json();
            data.forEach(a => {
                const li = document.createElement('li');
                li.innerText = a.name;
                li.onclick = function () {
                    auditorInput.value = a.name;
                    document.getElementById('auditor_nik').value = a.nik ?? '';
                    document.getElementById('auditor_department').value = a.department ?? '';
                    suggestionBox.innerHTML = '';
                };
                suggestionBox.appendChild(li);
            });
        }
    } catch (e) { console.log("API Error or not found"); }
});

let responderIndex = 0;
function addResponder() {
    const container = document.getElementById('responders');
    const html = `
        <div class="responder-box">
            <div style="display:flex; justify-content:space-between;">
                <strong>Responder #${responderIndex + 1}</strong>
                <button type="button" onclick="this.parentElement.parentElement.remove()" style="color:red; border:none; background:none;">Hapus</button>
            </div>
            <br>
            <input type="text" name="responders[${responderIndex}][name]" placeholder="Nama Responder" required>
            <div style="display: flex; gap: 10px;">
                <input type="text" name="responders[${responderIndex}][department]" placeholder="Departemen">
                <input type="text" name="responders[${responderIndex}][nik]" placeholder="NIK (opsional)">
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    responderIndex++;
}
</script>
</body>
</html>