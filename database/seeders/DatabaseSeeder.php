<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Document;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'demo@jobtracker.test'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        $apps = [
            [
                'company' => 'PT Tokopedia', 'title' => 'Senior Backend Engineer', 'status' => 'HR Interview',
                'job_posting_link' => 'https://www.tokopedia.com/careers/senior-backend-engineer',
                'contact' => 'Rina - recruiter@tokopedia.com', 'application_date' => '2026-09-20',
                'salary_range' => 'Rp 25jt - 35jt / bln', 'notes' => 'Di referensikan oleh teman (Andi).',
                'interviews' => [
                    ['stage' => 'Screening', 'interviewer' => 'Rina (Talent Acquisition)', 'schedule_date' => '2026-09-24 10:00', 'link_meeting' => 'https://meet.google.com/abc-mnop-qr', 'notes' => 'Diskusi ekspektasi gaji.'],
                    ['stage' => 'Technical Test', 'interviewer' => 'Bagas (Engineering Manager)', 'schedule_date' => '2026-10-05 14:00', 'link_meeting' => 'https://zoom.us/j/98765', 'notes' => 'Live coding: REST API + SQL.'],
                ],
            ],
            [
                'company' => 'Gojek', 'title' => 'Data Analyst', 'status' => 'Assessment',
                'job_posting_link' => 'https://www.gojek.com/careers/data-analyst',
                'contact' => '0812-3456-7890', 'application_date' => '2026-09-18',
                'salary_range' => 'Rp 18jt - 22jt / bln', 'notes' => 'Tugas SQL take-home diberikan deadline 3 hari.',
                'interviews' => [
                    ['stage' => 'HR Interview', 'interviewer' => 'Sinta (People Ops)', 'schedule_date' => '2026-09-28 09:00', 'link_meeting' => null, 'notes' => 'On-site di HQ.'],
                ],
            ],
            [
                'company' => 'Bank Mandiri', 'title' => 'IT Management Associate', 'status' => 'User Interview',
                'application_date' => '2026-09-10',
                'salary_range' => 'Rp 15jt - 18jt / bln', 'notes' => 'Proses via portal karier BUMN.',
                'interviews' => [
                    ['stage' => 'Screening', 'interviewer' => 'Tim Rekrutmen Mandiri', 'schedule_date' => '2026-09-15 13:00', 'link_meeting' => 'https://zoom.us/j/11122', 'notes' => null],
                    ['stage' => 'HR Interview', 'interviewer' => 'Ibu Maya (HRBP)', 'schedule_date' => '2026-09-22 10:00', 'link_meeting' => 'https://zoom.us/j/11123', 'notes' => 'Tanya soal kesiapan relokasi.'],
                    ['stage' => 'User Interview', 'interviewer' => 'Pak Hendra (Div Head IT)', 'schedule_date' => '2026-10-07 09:30', 'link_meeting' => 'https://zoom.us/j/11124', 'notes' => 'Final interview dengan divisi.'],
                ],
            ],
            [
                'company' => 'Stripe', 'title' => 'Software Engineer, New Grad', 'status' => 'Rejected',
                'job_posting_link' => 'https://stripe.com/jobs/software-engineer-new-grad',
                'contact' => 'apac-recruiting@stripe.com', 'application_date' => '2026-08-30',
                'salary_range' => 'SGD 6.5k - 8k / bln', 'notes' => 'Ditolak setelah technical interview.',
                'interviews' => [
                    ['stage' => 'Technical Test', 'interviewer' => 'Kevin (SWE)', 'schedule_date' => '2026-09-05 09:00', 'link_meeting' => 'https://zoom.us/j/555', 'notes' => 'Algo round: graph problem.'],
                ],
            ],
            [
                'company' => 'Traveloka', 'title' => 'Product Designer', 'status' => 'Wishlist',
                'job_posting_link' => 'https://www.traveloka.com/careers/product-designer',
                'application_date' => null,
                'salary_range' => 'Rp 14jt - 20jt / bln', 'notes' => 'Revisi portofolio dulu sebelum apply.',
                'interviews' => [],
            ],
            [
                'company' => 'Ruangguru', 'title' => 'Fullstack Developer', 'status' => 'Applied',
                'job_posting_link' => 'https://ruangguru.com/karir/fullstack-developer',
                'contact' => 'talent@ruangguru.com', 'application_date' => '2026-09-28',
                'salary_range' => 'Rp 16jt - 24jt / bln', 'notes' => 'Menunggu respon sejak 1 minggu lalu.',
                'interviews' => [],
            ],
            [
                'company' => 'Startup X (tidak jelas)', 'title' => 'DevOps Engineer', 'status' => 'Ghosted',
                'application_date' => '2026-08-15',
                'notes' => 'Tidak ada kabar setelah HR interview, >1 bulan.',
                'interviews' => [
                    ['stage' => 'HR Interview', 'interviewer' => 'Tidak dicatat', 'schedule_date' => '2026-08-20 15:00', 'link_meeting' => null, 'notes' => null],
                ],
            ],
        ];

        foreach ($apps as $data) {
            $interviews = $data['interviews'];
            unset($data['interviews']);

            $app = $user->applications()->create($data);

            foreach ($interviews as $iv) {
                $app->interviews()->create([
                    'interview_stage' => $iv['stage'],
                    'interviewer' => $iv['interviewer'],
                    'schedule_date' => $iv['schedule_date'],
                    'link_meeting' => $iv['link_meeting'],
                    'notes' => $iv['notes'],
                ]);
            }
        }
    }
}
