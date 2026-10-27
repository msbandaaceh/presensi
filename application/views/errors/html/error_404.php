<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="assets/icon/siap32.png" />
<meta name="robots" content="noindex">
<title>404 — Halaman Tidak Ditemukan</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}

  body{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Segoe UI',system-ui,-apple-system,sans-serif;
    background:#0a0e17;
    color:#e2e8f0;
    overflow:hidden;
    position:relative;
  }

  body::before{
    content:'';
    position:fixed;inset:0;
    background-image:
      linear-gradient(rgba(99,102,241,.06) 1px,transparent 1px),
      linear-gradient(90deg,rgba(99,102,241,.06) 1px,transparent 1px);
    background-size:48px 48px;
    mask-image:radial-gradient(ellipse 70% 70% at 50% 50%,#000 40%,transparent 100%);
    -webkit-mask-image:radial-gradient(ellipse 70% 70% at 50% 50%,#000 40%,transparent 100%);
  }

  .glow{
    position:fixed;border-radius:50%;filter:blur(110px);
    pointer-events:none;opacity:.45;
  }
  .glow-a{width:480px;height:480px;top:-120px;left:-100px;background:#4f46e5}
  .glow-b{width:420px;height:420px;bottom:-140px;right:-80px;background:#0e7490}

  .card{
    position:relative;
    width:min(560px,calc(100% - 40px));
    text-align:center;
    padding:64px 48px 56px;
    background:rgba(15,20,35,.72);
    border:1px solid rgba(99,102,241,.25);
    border-radius:24px;
    backdrop-filter:blur(24px);
    -webkit-backdrop-filter:blur(24px);
    box-shadow:
      0 0 0 1px rgba(255,255,255,.03) inset,
      0 30px 80px -20px rgba(0,0,0,.7);
    animation:rise .8s cubic-bezier(.22,1,.36,1) both;
  }

  @keyframes rise{
    from{opacity:0;transform:translateY(28px) scale(.97)}
    to{opacity:1;transform:translateY(0) scale(1)}
  }

  .compass-wrap{
    width:104px;height:104px;margin:0 auto 32px;
    position:relative;
    animation:drift 4s ease-in-out infinite;
  }
  @keyframes drift{
    0%,100%{transform:rotate(-4deg)}
    50%{transform:rotate(4deg)}
  }
  .compass-ring{
    position:absolute;inset:0;border-radius:50%;
    border:2px solid rgba(56,189,248,.45);
  }
  .compass-ring::before{
    content:'';position:absolute;
    width:8px;height:8px;border-radius:50%;
    background:#38bdf8;bottom:-4px;left:50%;
    transform:translateX(-50%);
    box-shadow:0 0 12px 2px rgba(56,189,248,.7);
  }
  .compass-body{
    position:absolute;inset:22px;
    background:linear-gradient(160deg,#0c4a6e,#082f49);
    border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 8px 24px rgba(14,116,144,.4);
  }
  .compass-body svg{width:44px;height:44px;stroke:#7dd3fc}

  .code{
    font-size:clamp(64px,12vw,88px);
    font-weight:800;
    letter-spacing:4px;
    line-height:1;
    background:linear-gradient(135deg,#38bdf8 0%,#22d3ee 50%,#818cf8 100%);
    -webkit-background-clip:text;background-clip:text;
    color:transparent;
    margin-bottom:10px;
  }
  .title{
    font-size:22px;font-weight:700;color:#f1f5f9;margin-bottom:12px;
  }
  .desc{
    font-size:14.5px;line-height:1.8;color:#94a3b8;
    max-width:380px;margin:0 auto 36px;
  }
  .desc strong{color:#a5f3fc;font-weight:600}

  .btn{
    display:inline-block;
    padding:13px 34px;
    font-size:14px;font-weight:600;letter-spacing:.5px;
    color:#e0e7ff;
    background:linear-gradient(135deg,#0e7490,#155e75);
    border:1px solid rgba(125,211,252,.4);
    border-radius:12px;
    text-decoration:none;
    transition:all .25s ease;
    box-shadow:0 6px 20px -6px rgba(14,116,144,.55);
  }
  .btn:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 28px -6px rgba(14,116,144,.7);
    background:linear-gradient(135deg,#0891b2,#0e7490);
  }

  .hint{
    margin-top:28px;font-size:12px;color:#475569;letter-spacing:.4px;
  }
  .hint code{
    background:rgba(56,189,248,.12);
    color:#7dd3fc;
    padding:2px 8px;border-radius:6px;
    font-family:Consolas,'Courier New',monospace;font-size:11.5px;
  }
</style>
</head>
<body>

<div class="glow glow-a"></div>
<div class="glow glow-b"></div>

<div class="card">
  <div class="compass-wrap">
    <div class="compass-ring"></div>
    <div class="compass-body">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="9"/>
        <polygon points="15.5,8.5 13.5,13.5 8.5,15.5 10.5,10.5" fill="#7dd3fc" stroke="none"/>
        <line x1="12" y1="3" x2="12" y2="5.5"/>
        <line x1="12" y1="18.5" x2="12" y2="21"/>
        <line x1="3" y1="12" x2="5.5" y2="12"/>
        <line x1="18.5" y1="12" x2="21" y2="12"/>
      </svg>
    </div>
  </div>

  <div class="code">404</div>
  <div class="title">Halaman Tidak Ditemukan</div>
  <p class="desc">
    Sepertinya Anda tersesat — alamat yang Anda cari
    <strong>tidak ada di server ini</strong>.
    Periksa kembali tautannya, atau kembali ke halaman utama.
  </p>

  <a class="btn" href="<?php echo base_url(); ?>">← Kembali ke Beranda</a>

</div>

</body>
</html>
