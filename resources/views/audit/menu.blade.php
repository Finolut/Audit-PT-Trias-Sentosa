<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Internal Audit Charter | PT Trias Sentosa Tbk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a365d',
                        secondary: '#2563eb',
                        accent: '#10b981'
                    }
                }
            }
        }
    </script>
    <style>
        /* Header styling */
        .header-section {
            background: linear-gradient(135deg, #1a365d 0%, #2563eb 100%);
            color: white;
            padding: 3rem 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none" stroke="%232563eb" stroke-width="0.5" opacity="0.1"/></svg>');
            opacity: 0.1;
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-subtitle {
            font-size: 1.1rem;
            font-weight: 400;
            max-width: 800px;
            line-height: 1.6;
            opacity: 0.9;
        }

        /* Form sections */
        .section-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a365d;
            margin-bottom: 1.2rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-description {
            color: #475569;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%2364748b' d='M2 0L0 2h4L2 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 0.8em;
        }

        /* Button styling */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: #2563eb;
            border: 1px solid #2563eb;
        }

        .btn-outline:hover {
            background: #dbeafe;
        }

        /* Conflict warning */
        .conflict-warning {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .conflict-icon {
            color: #ef4444;
            font-size: 1.2rem;
        }

        .conflict-text {
            color: #991b1b;
            font-weight: 500;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header-title {
                font-size: 1.8rem;
            }
            
            .header-subtitle {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 1.2rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Header Section -->
    <header class="header-section">
        <div class="max-w-7xl mx-auto">
            <h1 class="header-title">INTERNAL AUDIT CHARTER</h1>
            <p class="header-subtitle">Official charter defining the objectives, scope, and criteria of internal audits in accordance with ISO 14001.</p>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Internal Audit Charter | PT Trias Sentosa Tbk</h2>
            <button class="btn btn-primary">
                <i class="fas fa-file-alt"></i> Lanjutkan Audit
            </button>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <form>
                <!-- Identitas & Standar Audit Section -->
                <div class="section-card">
                    <h2 class="section-title">Identitas & Standar Audit</h2>
                    <p class="section-description">Formalize the foundational elements of your audit engagement per ISO 19011 requirements</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Jenis Pemeriksaan</label>
                            <select class="form-control form-select">
                                <option>Pihak Pertama (Internal Rutin)</option>
                                <option>Pemeriksaan Lanjutan (Corrective Action)</option>
                                <option>Investigasi Khusus (Insidentil)</option>
                                <option>Audit Mendadak (Unannounced)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Referensi Standar / Kriteria Audit</label>
                            <input type="text" class="form-control" 
                                   placeholder="Pilih standar yang relevan..." 
                                   value="ISO 14001:2015 (Environmental Management System)">
                        </div>
                    </div>
                </div>

                <!-- Tujuan & Lingkup Section -->
                <div class="section-card">
                    <h2 class="section-title">Tujuan & Lingkup (Objective & Scope)</h2>
                    <p class="section-description">Define the strategic purpose and operational boundaries of the audit engagement</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Audit Objective (Tujuan)</label>
                            <textarea class="form-control" rows="4" placeholder="Contoh: Mengevaluasi efektivitas pengendalian stok gudang dan kepatuhan terhadap prosedur FIFO.">Mengevaluasi efektivitas pengendalian stok gudang dan kepatuhan terhadap prosedur FIFO.</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Audit Scope (Lingkup)</label>
                            <textarea class="form-control" rows="4" placeholder="Pilih batasan area audit...">Proses Pengadaan
Proses Produksi
Proses Keuangan
Fisik Aset / Inventaris
Kompetensi SDM
Keamanan Data / IT</textarea>
                        </div>
                    </div>
                </div>

                <!-- Metodologi Pemeriksaan Section -->
                <div class="section-card">
                    <h2 class="section-title">Metodologi Pemeriksaan</h2>
                    <p class="section-description">Document the methodology that will be used to conduct the audit</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Metodologi Pemeriksaan</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="doc-review" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <label for="doc-review" class="ml-2 text-gray-700">Document Review</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="interview" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <label for="interview" class="ml-2 text-gray-700">Wawancara (Interview)</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="field-obs" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <label for="field-obs" class="ml-2 text-gray-700">Observasi Lapangan</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="physical-sampling" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <label for="physical-sampling" class="ml-2 text-gray-700">Sampling Fisik</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control" rows="4" placeholder="Masukkan catatan metodologi tambahan jika diperlukan..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Tim Pemeriksa Section -->
                <div class="section-card">
                    <h2 class="section-title">Tim Pemeriksa (Audit Team)</h2>
                    <p class="section-description">Ensure team composition meets independence requirements per ISO 19011 clause 5.3</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Lead Auditor</label>
                            <input type="text" class="form-control" placeholder="Nama Lead Auditor" value="Brahmanto Anggoro Laksono">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email Lead Auditor (opsional)</label>
                            <input type="email" class="form-control" placeholder="Digunakan untuk komunikasi dan distribusi laporan audit." value="brahmanto@trias-sentosa.co.id">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label">Anggota Tim Tambahan</label>
                        <div class="flex flex-wrap gap-2">
                            <div class="flex items-center bg-gray-100 rounded-lg px-3 py-1.5">
                                <span class="text-gray-700 mr-2">Siti Aminah (Auditor)</span>
                                <button type="button" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="flex items-center bg-gray-100 rounded-lg px-3 py-1.5">
                                <span class="text-gray-700 mr-2">Ahmad Fauzi (Expert)</span>
                                <button type="button" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <button type="button" class="btn btn-outline">
                                <i class="fas fa-plus"></i> Tambah Auditor / Observer / Expert
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Target Audit & Jadwal Section -->
                <div class="section-card">
                    <h2 class="section-title">Target Audit & Jadwal</h2>
                    <p class="section-description">Define the auditee departments and schedule the audit execution period.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Departemen Auditee</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="dept-prod" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <label for="dept-prod" class="ml-2 text-gray-700">Produksi</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="dept-qc" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <label for="dept-qc" class="ml-2 text-gray-700">Quality Control</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="dept-warehouse" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <label for="dept-warehouse" class="ml-2 text-gray-700">Gudang</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="dept-finance" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <label for="dept-finance" class="ml-2 text-gray-700">Keuangan</label>
                                </div>
                            </div>
                            
                            <div class="conflict-warning mt-3">
                                <i class="fas fa-exclamation-triangle conflict-icon"></i>
                                <span class="conflict-text">KONFLIK: Lead Auditor berasal dari salah satu departemen yang dipilih!</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">Audit dapat mencakup lebih dari satu departemen dalam satu penugasan.</p>
                        </div>
                        
                        <div class="form-group">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="form-label">Tanggal Mulai Audit</label>
                                    <input type="date" class="form-control" value="2026-01-31">
                                </div>
                                <div>
                                    <label class="form-label">Tanggal Selesai Audit</label>
                                    <input type="date" class="form-control" value="2026-02-05">
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">Rentang tanggal digunakan untuk audit yang berlangsung lebih dari satu hari.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="section-card">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <button type="button" class="btn btn-outline w-full sm:w-auto">
                            <i class="fas fa-undo"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary w-full sm:w-auto">
                            <i class="fas fa-check-circle"></i> Start Audit Process
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto px-4 sm:px-6 py-6 text-center text-gray-500 text-sm">
        <p>© 2026 PT Trias Sentosa Tbk. All rights reserved. | Internal Audit Management System</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple validation for date fields
            const startDate = document.querySelector('input[type="date"]');
            startDate.min = new Date().toISOString().split('T')[0];
            
            // Button interaction
            document.querySelector('.btn-primary').addEventListener('click', function(e) {
                e.preventDefault();
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check-circle"></i> Start Audit Process';
                    alert('Audit process has started successfully!');
                }, 1500);
            });
            
            // Conflict warning toggle
            const conflictWarning = document.querySelector('.conflict-warning');
            if (conflictWarning) {
                setTimeout(() => {
                    conflictWarning.style.opacity = 0.8;
                    setTimeout(() => {
                        conflictWarning.style.opacity = 1;
                    }, 100);
                }, 500);
            }
        });
    </script>
</body>
</html>