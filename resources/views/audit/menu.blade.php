<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Audit – {{ $deptName }}</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
:root{
  --blue:#0f2a44;
  --yellow:#facc15;
  --bg:#f8fafc;
  --border:#e5e7eb;
}

*{box-sizing:border-box}
body{
  margin:0;
  font-family:Inter,sans-serif;
  background:var(--bg);
  color:#111;
}

.navbar{
  background:var(--blue);
  color:white;
  padding:1rem 2rem;
  font-weight:700;
  display:flex;
  justify-content:space-between;
}

.container{
  max-width:900px;
  margin:2rem auto;
  padding:0 1rem;
}

.card{
  background:white;
  border:1px solid var(--border);
  border-radius:12px;
  padding:1.5rem;
  margin-bottom:1.5rem;
}

.audit-title{
  font-size:1.5rem;
  font-weight:700;
}

.meta{
  color:#555;
  margin-top:.5rem;
}

.progress{
  margin-top:1rem;
  height:8px;
  background:#e5e7eb;
  border-radius:6px;
  overflow:hidden;
}
.progress span{
  display:block;
  height:100%;
  width:{{ round(($completedCount ?? 0)/count($mainClauses)*100) }}%;
  background:var(--yellow);
}

.token-box{
  background:#fffbe6;
  border:1px solid #fde68a;
  padding:1rem;
  border-radius:10px;
  margin-top:1rem;
}

.token-box strong{
  display:block;
  margin-bottom:.25rem;
}

.token{
  font-family:monospace;
  font-size:1.1rem;
  font-weight:700;
}

.clause{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:1rem;
  border-bottom:1px solid var(--border);
}

.clause:last-child{border:none}

.btn{
  padding:.5rem 1.2rem;
  border-radius:8px;
  border:2px solid var(--blue);
  background:var(--blue);
  color:white;
  text-decoration:none;
  font-weight:600;
}

.btn.outline{
  background:white;
  color:var(--blue);
}
</style>
</head>

<body>

<div class="navbar">
  <div>INTERNAL AUDIT</div>
  <div>{{ date('Y') }}</div>
</div>

<div class="container">

  <div class="card">
    <div class="audit-title">Audit – {{ $deptName }}</div>
    <div class="meta">
      Auditor: <strong>{{ $auditorName }}</strong><br>
      Progress: {{ $completedCount ?? 0 }} / {{ count($mainClauses) }}
    </div>

    <div class="progress"><span></span></div>

    <div class="token-box">
      <strong>⚠ SIMPAN TOKEN INI</strong>
      Token digunakan untuk melanjutkan audit jika sesi terputus.
      <div class="token">{{ $resumeToken ?? '-' }}</div>
    </div>
  </div>

  <div class="card">
    @foreach($mainClauses as $code)
      <div class="clause">
        <div>
          <strong>Klausul {{ $code }}</strong><br>
          <small>{{ $titles[$code] ?? '' }}</small>
        </div>
        <a class="btn {{ ($clauseProgress[$code]['percentage'] ?? 0) == 100 ? 'outline' : '' }}"
           href="{{ route('audit.show',['id'=>$auditId,'clause'=>$code]) }}">
          {{ ($clauseProgress[$code]['percentage'] ?? 0) == 100 ? 'Detail' : 'Lanjutkan' }}
        </a>
      </div>
    @endforeach
  </div>

</div>

</body>
</html>
