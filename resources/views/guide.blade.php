<x-app-layout>
    <div class="px-6 lg:px-8 py-8 max-w-4xl">

        <div class="mb-8">
            <h1 class="text-xl font-semibold text-gray-900 tracking-tight">User Guide</h1>
            <p class="text-sm text-gray-500 mt-1">Cara kerja setiap bagian JobTracker Pro — dari lamaran pertama sampai tawaran kerja.</p>
        </div>

        <nav class="border border-gray-200 rounded-lg bg-white p-5 mb-8">
            <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">Isi panduan</div>
            <ol class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1.5 text-sm">
                <li><a href="#getting-started" class="text-gray-600 hover:text-gray-900 transition-colors">1. Mulai: lamaran pertama</a></li>
                <li><a href="#table" class="text-gray-600 hover:text-gray-900 transition-colors">2. Tabel aplikasi</a></li>
                <li><a href="#board" class="text-gray-600 hover:text-gray-900 transition-colors">3. Pipeline (kanban)</a></li>
                <li><a href="#detail" class="text-gray-600 hover:text-gray-900 transition-colors">4. Detail &amp; wawancara</a></li>
                <li><a href="#status" class="text-gray-600 hover:text-gray-900 transition-colors">5. Alur status</a></li>
                <li><a href="#followup" class="text-gray-600 hover:text-gray-900 transition-colors">6. Pengingat follow-up</a></li>
                <li><a href="#stale" class="text-gray-600 hover:text-gray-900 transition-colors">7. Deteksi lamaran mandek</a></li>
                <li><a href="#dashboard" class="text-gray-600 hover:text-gray-900 transition-colors">8. Dashboard</a></li>
                <li><a href="#documents" class="text-gray-600 hover:text-gray-900 transition-colors">9. Dokumen</a></li>
                <li><a href="#export" class="text-gray-600 hover:text-gray-900 transition-colors">10. Ekspor data</a></li>
                <li><a href="#shortcuts" class="text-gray-600 hover:text-gray-900 transition-colors">11. Pintasan keyboard</a></li>
            </ol>
        </nav>

        <section id="getting-started" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">1. Mulai: lamaran pertama</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p>Lamaran baru dibuat lewat tombol <strong class="text-gray-900">New Application</strong> di header (pojok kanan atas), atau lewat command palette (<kbd class="border border-gray-200 bg-gray-50 rounded px-1 text-[11px] text-gray-500">⌘K</kbd> lalu ketik "New").</p>
                <p>Wajib diisi: <strong class="text-gray-900">perusahaan, posisi, dan status</strong>. Sisanya opsional — tautan lowongan, kontak, tanggal, range gaji, catatan, dan pengingat follow-up.</p>
                <p>Setelah disimpan, lamaran langsung muncul di tabel, di pipeline, dan dihitung di dashboard. Setiap perubahan memberi notifikasi kecil (toast) di pojok kanan bawah.</p>
            </div>
        </section>

        <section id="table" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">2. Tabel aplikasi</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p>Halaman <a class="text-gray-900 font-medium hover:underline" href="{{ route('applications.index') }}">Applications</a> adalah pusat kontrol harian:</p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li><strong class="text-gray-900">Cari &amp; filter</strong> — kolom pencarian mencocokkan nama perusahaan atau posisi; dropdown status memfilter satu tahap sekaligus. Tombol Reset memulihkan daftar penuh.</li>
                    <li><strong class="text-gray-900">Urutkan</strong> — klik header <em>Company</em> (A–Z) atau <em>Date</em> (baru ↔ lama). Filter tetap dijaga saat pengurutan.</li>
                    <li><strong class="text-gray-900">Ganti status langsung dari tabel</strong> — sel Status adalah dropdown bergaya badge: hijau untuk Offered, merah untuk Rejected/Ghosted. Pilih nilai baru dan perubahan langsung tersimpan, tanpa membuka halaman edit.</li>
                    <li><strong class="text-gray-900">Tanda mandek</strong> — titik kuning di samping nama perusahaan berarti lamaran tidak bergerak cukup lama (bagian 7).</li>
                    <li><strong class="text-gray-900">Aksi per baris</strong> — buka detail, edit, atau hapus (meminta konfirmasi; wawancara &amp; berkas terkait ikut terhapus).</li>
                </ul>
            </div>
        </section>

        <section id="board" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">3. Pipeline (papan kanban)</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p>Papan <a class="text-gray-900 font-medium hover:underline" href="{{ route('applications.board') }}">Pipeline</a> menampilkan semua lamaran sebagai kartu di 8 kolom status. Kartu bisa <strong class="text-gray-900">di-drag antar kolom</strong> — lepaskan di kolom tujuan dan status tersimpan otomatis. Jika penyimpanan gagal, kartu kembali ke kolom semula dengan peringatan.</p>
                <p>Kolom kosong menampilkan kotak putus-putus. Tiap kartu memuat perusahaan, posisi, tanggal, dan range gaji; klik namanya untuk membuka detail. Tombol "Table view" / "Board view" memudahkan berpindah tampilan.</p>
            </div>
        </section>

        <section id="detail" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">4. Detail lamaran &amp; wawancara</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p>Halaman detail memuat ringkasan, timeline status, berkas terkait, dan tiga panel kerja utama:</p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li><strong class="text-gray-900">Wawancara</strong> — tambah, ubah, hapus sesi: tahap (HR/User), pewawancara, tanggal-waktu, tautan meeting, dan hasil. Muncul juga di halaman Interviews dan dashboard.</li>
                    <li><strong class="text-gray-900">Follow-up</strong> — kartu untuk mengatur tanggal pengingat + catatan pendek (mis. "tanya timeline rekrutmen").</li>
                    <li><strong class="text-gray-900">Berkas</strong> — lampirkan CV, portofolio, atau sertifikat khusus perusahaan ini; unduh atau hapus.</li>
                </ul>
            </div>
        </section>

        <section id="status" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">5. Alur status lamaran</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p>Delapan status menggambarkan seluruh siklus pencarian kerja:</p>
                <div class="flex flex-wrap gap-2 my-2">
                    @foreach ([
                        ['Wishlist', 'menarik perhatian'],
                        ['Applied', 'sudah dilamar'],
                        ['Assessment', 'tes / asesmen'],
                        ['HR Interview', 'wawancara HR'],
                        ['User Interview', 'wawancara panel / teknis'],
                        ['Offered', 'tawaran masuk'],
                        ['Rejected', 'ditolak'],
                        ['Ghosted', 'hilang tanpa kabar'],
                    ] as [$label, $desc])
                        <span class="border border-gray-200 rounded-md px-2 py-1 text-xs"><span class="font-medium text-gray-900">{{ $label }}</span> <span class="text-gray-400">— {{ $desc }}</span></span>
                    @endforeach
                </div>
                <p>Status bisa diubah dari mana saja (dropdown tabel, drag di papan, form edit, atau halaman detail). Urutan tidak dipaksa — lamaran bisa mundur dari User Interview ke Assessment bila perusahaan meminta tes ulang.</p>
            </div>
        </section>

        <section id="followup" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">6. Pengingat follow-up</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p>Setiap lamaran boleh punya <strong class="text-gray-900">tanggal follow-up</strong> dan catatan pendek. Atur lewat form lamaran, halaman edit, atau kartu "Follow-up" di detail. Perilaku sistem:</p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li>Tanggal <strong class="text-gray-900">hari ini</strong> → tampil di widget <em>Follow-ups due</em> dashboard dengan label "Today".</li>
                    <li>Tanggal <strong class="text-gray-900">lewat</strong> → tetap tampil dengan label merah "overdue" sampai diselesaikan (hapus atau geser ke depan).</li>
                    <li>Tanggal <strong class="text-gray-900">masa depan</strong> → belum tampil; muncul otomatis saat tiba.</li>
                </ul>
                <p>Praktis: setelah interview, set follow-up 3 hari kemudian dengan catatan "follow up hasil seleksi".</p>
            </div>
        </section>

        <section id="stale" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">7. Deteksi lamaran mandek</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p>JobTracker otomatis menandai lamaran aktif yang tidak bergerak:</p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li><strong class="text-gray-900">Wishlist / Applied / Assessment</strong> lebih dari <strong class="text-gray-900">14 hari</strong>.</li>
                    <li><strong class="text-gray-900">HR / User Interview</strong> lebih dari <strong class="text-gray-900">7 hari</strong>.</li>
                </ul>
                <p>Hasilnya: titik kuning di tabel dan daftar <em>Stalled applications</em> di dashboard. Tindak lanjut wajar: kirim follow-up, tandai <em>Ghosted</em>, atau tutup dengan <em>Rejected</em>.</p>
            </div>
        </section>

        <section id="dashboard" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">8. Dashboard</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p><a class="text-gray-900 font-medium hover:underline" href="{{ route('dashboard') }}">Dashboard</a> merangkum seluruh pencarian kerja dalam sekejap:</p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li><strong class="text-gray-900">Empat angka atas</strong> — total lamaran, yang berjalan, tawaran masuk, dan tingkat konversi ke tahap interview.</li>
                    <li><strong class="text-gray-900">Pipeline funnel</strong> — proporsi lamaran per tahap (Applied → Assessment → Interview → Offer), memperlihatkan di mana pipa menipis.</li>
                    <li><strong class="text-gray-900">Status breakdown</strong> — donut distribusi status beserta persentasenya.</li>
                    <li><strong class="text-gray-900">Stalled &amp; follow-ups</strong> — lamaran mandek dan pengingat yang jatuh tempo.</li>
                    <li><strong class="text-gray-900">Upcoming interviews</strong> — lima wawancara berikutnya dengan tautan meeting.</li>
                    <li><strong class="text-gray-900">Recent applications</strong> — lima lamaran terbaru beserta statusnya.</li>
                </ul>
            </div>
        </section>

        <section id="documents" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">9. Dokumen</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p>Halaman <a class="text-gray-900 font-medium hover:underline" href="{{ route('documents.index') }}">Documents</a> adalah laci dokumen utama (CV, portofolio, referensi). Setiap dokumen diberi nama, tipe (CV / Resume / Portfolio / Certificate / Other), dan bisa terkait ke satu lamaran atau bersifat umum. Upload lewat tombol di atas; unduh atau hapus dari baris mana pun. Dokumen khusus lamaran juga bisa ditambahkan langsung dari halaman detail lamaran.</p>
            </div>
        </section>

        <section id="export" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">10. Ekspor data</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-3 text-sm text-gray-600 leading-relaxed">
                <p>Tombol <strong class="text-gray-900">Export CSV</strong> di toolbar tabel mengunduh seluruh lamaran sebagai file CSV — dan <em>mengikuti filter aktif</em>. Contoh: filter status "Offered" lalu ekspor, hasilnya hanya lamaran berstatus Offered. Kolom: perusahaan, posisi, status, range gaji, tanggal, kontak, tautan lowongan, dan catatan. Cocok untuk arsip pribadi atau analisis di spreadsheet.</p>
            </div>
        </section>

        <section id="shortcuts" class="mb-8 scroll-mt-20">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">11. Pintasan keyboard</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 space-y-4 text-sm text-gray-600 leading-relaxed">
                <p><strong class="text-gray-900">Command palette</strong> — pintu cepat ke halaman dan lamaran Anda:</p>
                <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-2 px-4 w-48">Kombinasi</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-2 px-4">Fungsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-2.5 px-4"><kbd class="border border-gray-200 bg-gray-50 rounded px-1.5 py-0.5 text-xs">⌘K</kbd> / <kbd class="border border-gray-200 bg-gray-50 rounded px-1.5 py-0.5 text-xs">Ctrl+K</kbd></td>
                            <td class="py-2.5 px-4 text-gray-600">Buka / tutup command palette</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4"><kbd class="border border-gray-200 bg-gray-50 rounded px-1.5 py-0.5 text-xs">↑</kbd> <kbd class="border border-gray-200 bg-gray-50 rounded px-1.5 py-0.5 text-xs">↓</kbd></td>
                            <td class="py-2.5 px-4 text-gray-600">Navigasi hasil (ketik untuk menyaring lamaran)</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4"><kbd class="border border-gray-200 bg-gray-50 rounded px-1.5 py-0.5 text-xs">↵</kbd></td>
                            <td class="py-2.5 px-4 text-gray-600">Buka hasil terpilih</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4"><kbd class="border border-gray-200 bg-gray-50 rounded px-1.5 py-0.5 text-xs">Esc</kbd></td>
                            <td class="py-2.5 px-4 text-gray-600">Tutup palette</td>
                        </tr>
                    </tbody>
                </table>
                <p>Ketika palette terbuka, ketik nama perusahaan (mis. "Tokopedia") untuk melompat ke lamaran tertentu, atau nama halaman untuk navigasi.</p>
            </div>
        </section>

        <section class="mb-4">
            <h2 class="text-base font-semibold text-gray-900 tracking-tight mb-3">Saran alur kerja mingguan</h2>
            <div class="border border-gray-200 rounded-lg bg-white p-5 text-sm text-gray-600 leading-relaxed space-y-2">
                <p><strong class="text-gray-900">Awal minggu</strong> — buka dashboard, selesaikan semua follow-up yang jatuh tempo, dan beri lamaran mandek tindak lanjut (follow-up, tandai Ghosted, atau tutup).</p>
                <p><strong class="text-gray-900">Sehari-hari</strong> — ganti status begitu ada kabar; tambah sesi wawancara secepatnya setelah dijadwalkan; set pengingat 2–3 hari setelah interview.</p>
                <p><strong class="text-gray-900">Akhir minggu</strong> — tinjau funnel di dashboard: jika konversi Application → Interview rendah, sesuaikan cara menyaring lowongan (mis. lebih ketat di Wishlist).</p>
            </div>
        </section>
    </div>
</x-app-layout>
