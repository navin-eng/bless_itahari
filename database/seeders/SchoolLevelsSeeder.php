<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Str;

class SchoolLevelsSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            [
                'name' => 'Pre-Primary Level (Playgroup to UKG)',
                'slug' => 'pre-primary-level-pg-to-ukg',
                'academic_level' => 'Pre-Primary',
                'grade_span' => 'Playgroup (PG), Nursery, LKG, UKG (Ages 3 - 5)',
                'duration' => 'Annual Academic Session',
                'semester' => 'Annual (Continuous Assessment)',
                'requirement' => 'Age 2.5+ & Municipal Birth Registration Certificate',
                'evaluation_system' => '100% Continuous Child Observation & Milestone Assessment (No formal examinations)',
                'starting_time' => '09:30 AM',
                'closing_time' => '02:30 PM',
                'image' => 'backend/images/courses/default.jpg',
                'gallery' => '[]',
                'status' => 1,
                'description' => 'A joyful, Montessori-inspired early childhood development wing fostering creativity, sensory exploration, language acquisition, and foundational socialization for toddlers and young learners.',
                'fulldescription' => '<div class="school-level-overview">
                    <p class="lead">At Bless Itahari, our Pre-Primary Wing provides a warm, stimulating, and child-centered environment where early learners take their first joyful steps into education. Inspired by Montessori principles and aligned with Nepal Curriculum Development Centre (CDC) early childhood guidelines, we celebrate each child\'s unique pace of development.</p>
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-balloon-heart me-2"></i>Play-Based Learning</h6>
                                <p class="small text-muted mb-0">Learning through concrete toys, sensorial materials, music, rhythmic movement, and thematic story sessions.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-shield-check me-2"></i>Safe & Caring Environment</h6>
                                <p class="small text-muted mb-0">Child-proof facilities, trained female care attendants, clean sanitized playrooms, and dedicated security monitoring.</p>
                            </div>
                        </div>
                    </div>
                </div>',
                'curriculum' => '<div class="curriculum-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-journal-bookmark me-2"></i>Early Childhood Curriculum Domains (CDC Nepal Aligned)</h5>
                    <p class="text-muted">Our curriculum integrates 6 fundamental developmental domains prescribed by Nepal\'s National Curriculum Framework for early childhood education:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex align-items-start gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                            <div><strong>1. Language & Communication:</strong> English and Nepali phonics, storytelling, vocabulary building, rhyme recitation, listening comprehension, and early stroke handwriting.</div>
                        </li>
                        <li class="list-group-item d-flex align-items-start gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                            <div><strong>2. Mathematical Concepts:</strong> Pre-math logic, shapes, spatial awareness, color classification, sorting, counting, and number recognition (1-100).</div>
                        </li>
                        <li class="list-group-item d-flex align-items-start gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                            <div><strong>3. Physical & Motor Development:</strong> Fine motor skills (clay molding, beading, buttoning, paper tearing) and gross motor skills (balancing, hopping, running, ball play).</div>
                        </li>
                        <li class="list-group-item d-flex align-items-start gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                            <div><strong>4. Socio-Emotional Development:</strong> Self-regulation, empathy, sharing, turn-taking, table manners, greeting teachers and peers, and building independence.</div>
                        </li>
                        <li class="list-group-item d-flex align-items-start gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                            <div><strong>5. Creative Arts & Expression:</strong> Free-hand drawing, finger painting, origami, dramatic role-play, folk and children\'s songs.</div>
                        </li>
                        <li class="list-group-item d-flex align-items-start gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                            <div><strong>6. Health, Nutrition & Self-Care:</strong> Handwashing routines, personal cleanliness, toilet training assistance, and healthy dietary habits.</div>
                        </li>
                    </ul>
                </div>',
                'rules' => '<div class="rules-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-clipboard-check me-2"></i>Pre-Primary Policies & Code of Conduct</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>School Hours:</strong> Classes run from 09:30 AM to 02:30 PM (Sunday to Friday). Gates open at 09:00 AM.</li>
                        <li class="list-group-item"><strong>Authorized Pick-up:</strong> Children are released strictly to guardians or designated caretakers holding the official school pickup card.</li>
                        <li class="list-group-item"><strong>Dress Code:</strong> Clean, comfortable school play-wear with velcro shoes for safety and ease of movement.</li>
                        <li class="list-group-item"><strong>Healthy Tiffin Policy:</strong> Parents must pack nutritious home-cooked tiffins. Commercial junk food, chips, and carbonated beverages are strictly prohibited.</li>
                        <li class="list-group-item"><strong>Health & Leave:</strong> In case of contagious illnesses (fever, flu, chickenpox), parents must notify the school and keep the child home until full recovery.</li>
                    </ul>
                </div>',
                'admission_procedure' => '<div class="admission-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-person-plus me-2"></i>Pre-Primary Admission Guidelines</h5>
                    <p class="text-muted">Admissions open annually in Chaitra/Baisakh for the upcoming academic session:</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr><th>Class / Level</th><th>Age Eligibility</th><th>Admission Assessment</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><strong>Playgroup (PG)</strong></td><td>2.5 to 3 Years</td><td>Friendly parent-child interaction (No written test)</td></tr>
                                <tr><td><strong>Nursery</strong></td><td>3 to 4 Years</td><td>Informal interactive observation</td></tr>
                                <tr><td><strong>LKG</strong></td><td>4 to 5 Years</td><td>Basic oral identification of colors, objects & letters</td></tr>
                                <tr><td><strong>UKG</strong></td><td>5 to 6 Years</td><td>Simple pencil grip & interactive communication</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <h6>Required Documents:</h6>
                    <ul class="text-muted">
                        <li>Photocopy of Municipal Birth Registration Certificate (जन्मदर्ता प्रमाणपत्र)</li>
                        <li>3 recent passport-size photographs of the child</li>
                        <li>1 passport-size photograph of both parents/guardians</li>
                        <li>Copy of Child Immunization / Vaccination Record</li>
                        <li>Copy of Parents\' Citizenship Card (नागरिकता)</li>
                    </ul>
                </div>',
            ],
            [
                'name' => 'Basic Level Education (Grades 1 to 8)',
                'slug' => 'basic-level-grades-1-to-8',
                'academic_level' => 'Basic Level',
                'grade_span' => 'Grades 1 to 8 (Ages 6 - 13)',
                'duration' => '8 Academic Years (Annual Session)',
                'semester' => 'Annual (Terminal Evaluations & Grade 8 BLE)',
                'requirement' => 'Prior Grade Promotion Marksheet & Transfer Certificate (TC)',
                'evaluation_system' => 'CAS (Continuous Assessment System) for Grades 1-5; Terminal Exams & BLE Board Exam (Grade 8) by Itahari Sub-Metropolitan City',
                'starting_time' => '09:30 AM',
                'closing_time' => '03:45 PM',
                'image' => 'backend/images/courses/default.jpg',
                'gallery' => '[]',
                'status' => 1,
                'description' => 'Comprehensive foundational schooling strictly adhering to the National Curriculum Framework (NCF) of Nepal. Covers integrated early learning (Grades 1-3), core disciplines (Grades 4-5), and prepares students for the Municipal Basic Level Examination (BLE) in Grade 8.',
                'fulldescription' => '<div class="school-level-overview">
                    <p class="lead">The Basic Level at Bless Itahari builds essential analytical, linguistic, scientific, and socio-cultural competencies. Operating under Nepal\'s Ministry of Education, Science & Technology and the Curriculum Development Centre (CDC), our program equips students with conceptual mastery and character development leading up to the Grade 8 Basic Level Examination (BLE).</p>
                    <div class="row g-4 mt-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-layers me-2"></i>Integrated Early Grades (1-3)</h6>
                                <p class="small text-muted mb-0">Cross-curricular theme-based learning integrating sciences, social studies, and languages without stressful memorization.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-book me-2"></i>Middle Basic (4-5)</h6>
                                <p class="small text-muted mb-0">Deepening inquiry in science, mathematics, computer literacy, and moral education with balanced CAS evaluations.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-award me-2"></i>Upper Basic & BLE (6-8)</h6>
                                <p class="small text-muted mb-0">Rigorous academic curriculum preparing students for the Grade 8 Board Examination conducted by Itahari Sub-Metropolitan City.</p>
                            </div>
                        </div>
                    </div>
                </div>',
                'curriculum' => '<div class="curriculum-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-journal-text me-2"></i>National Curriculum Framework (CDC Nepal)</h5>
                    
                    <div class="card mb-3 border">
                        <div class="card-header bg-light fw-bold">Grades 1 - 3 (Integrated Curriculum / एकीकृत पाठ्यक्रम)</div>
                        <div class="card-body">
                            <p class="text-muted small mb-2">Subject Areas:</p>
                            <ul class="mb-0 small">
                                <li><strong>Compulsory Nepali (नेपाली)</strong> — Language skills, creative expression, reading comprehension.</li>
                                <li><strong>Compulsory English</strong> — Foundational grammar, phonetics, speaking and writing fluently.</li>
                                <li><strong>Mathematics (गणित)</strong> — Numbers, arithmetic operations, measurement, and geometry.</li>
                                <li><strong>Our Surroundings (मेरो सेरोफेरो)</strong> — Integrated science, social studies, health, physical education, and creative arts.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card mb-3 border">
                        <div class="card-header bg-light fw-bold">Grades 4 - 5 (Foundation Subjects)</div>
                        <div class="card-body">
                            <ul class="mb-0 small">
                                <li>Compulsory Nepali, Compulsory English, Mathematics</li>
                                <li>Science and Technology (विज्ञान तथा प्रविधि) with practical hands-on demonstrations</li>
                                <li>Social Studies and Human Values (सामाजिक अध्ययन तथा मानव मूल्य)</li>
                                <li>Health, Physical & Creative Arts (स्वास्थ्य, शारीरिक तथा सिर्जनात्मक कला)</li>
                                <li>Computer Science & Digital Literacy fundamentals</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card mb-3 border">
                        <div class="card-header bg-light fw-bold">Grades 6 - 8 (Upper Basic & BLE Board Preparation)</div>
                        <div class="card-body">
                            <ul class="mb-0 small">
                                <li>Compulsory Nepali & Compulsory English</li>
                                <li>Compulsory Mathematics (Arithmetic, Algebra, Geometry, Statistics)</li>
                                <li>Science & Technology (Physics, Chemistry, Biology, Astronomy, Environmental Science)</li>
                                <li>Social Studies & Character Education (History, Geography, Civic Awareness, Peace & Human Rights)</li>
                                <li>Health & Physical Education</li>
                                <li>Computer Studies, Coding & Practical Lab Sessions</li>
                                <li>Local Curriculum / Mother Tongue / Vocational Skill Orientation</li>
                            </ul>
                        </div>
                    </div>
                </div>',
                'rules' => '<div class="rules-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-shield-exclamation me-2"></i>Rules, Regulations & Academic Discipline</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>Attendance:</strong> Minimum 75% verified classroom attendance is mandatory to sit for terminal examinations and the Grade 8 BLE exam.</li>
                        <li class="list-group-item"><strong>School Uniform:</strong> Full prescribed school uniform, clean ID card, and polished shoes required daily. Sports uniform on designated days.</li>
                        <li class="list-group-item"><strong>Punctuality:</strong> Assembly begins at 09:30 AM sharp. Late arrivals without prior written notice will be recorded in the student handbook.</li>
                        <li class="list-group-item"><strong>Electronics & Mobile Policy:</strong> Mobile phones, electronic entertainment devices, and smartwatches are strictly prohibited on campus.</li>
                        <li class="list-group-item"><strong>Homework & Diary:</strong> Students must maintain a school diary for daily assignments, which must be counter-signed by guardians.</li>
                        <li class="list-group-item"><strong>Examination Integrity:</strong> Any malpractice or copying during tests will result in immediate disqualification and disciplinary action.</li>
                    </ul>
                </div>',
                'admission_procedure' => '<div class="admission-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-pencil-square me-2"></i>Admission Process for Grades 1 to 8</h5>
                    <p class="text-muted">Admissions take place at the start of the academic year (Chaitra/Baisakh) subject to seat availability:</p>
                    <ol class="text-muted mb-4">
                        <li><strong>Inquiry & Application Form:</strong> Obtain the application form from the school administration desk or submit online via the Admissions portal.</li>
                        <li><strong>Entrance Assessment:</strong> Candidates appear for an assessment evaluating English, Nepali, and Mathematics aptitude for appropriate grade placement.</li>
                        <li><strong>Interview:</strong> Short conversation with the child and parents to understand learning needs and expectations.</li>
                        <li><strong>Confirmation & Enrollment:</strong> Submission of required documents and payment of admission fees to confirm enrollment.</li>
                    </ol>
                    <h6>Required Documentation:</h6>
                    <ul class="text-muted">
                        <li>Original Transfer Certificate (TC) from the previous school (signed by District/Municipal Education Authority for inter-district transfers)</li>
                        <li>Copy of Previous Year\'s Annual Report Card / Marksheet</li>
                        <li>Copy of Birth Registration Certificate (जन्मदर्ता)</li>
                        <li>Character Certificate from the previous school (for Grades 6 to 8)</li>
                        <li>3 passport-size color photographs</li>
                    </ul>
                </div>',
            ],
            [
                'name' => 'Secondary Level Education (Grades 9 & 10 / SEE)',
                'slug' => 'secondary-level-grades-9-10-see',
                'academic_level' => 'Secondary Level',
                'grade_span' => 'Grades 9 and 10 (Secondary Education Examination - SEE Track)',
                'duration' => '2 Academic Years (Annual Session)',
                'semester' => 'Annual (Terminals & National SEE Board Exam)',
                'requirement' => 'Passed Grade 8 BLE (Basic Level Examination) with verified Transfer Certificate',
                'evaluation_system' => 'National Letter Grading System (GPA 4.0): 25% Internal Continuous Assessment + 75% External SEE Board Examination',
                'starting_time' => '09:15 AM',
                'closing_time' => '04:00 PM',
                'image' => 'backend/images/courses/default.jpg',
                'gallery' => '[]',
                'status' => 1,
                'description' => 'Rigorous secondary schooling preparing learners for the nationwide Secondary Education Examination (SEE). Features intensive conceptual learning in science and mathematics, hands-on lab experiments, and tailored elective specializations.',
                'fulldescription' => '<div class="school-level-overview">
                    <p class="lead">Grades 9 and 10 represent a pivotal academic milestone in a student\'s educational journey in Nepal. At Bless Itahari, our Secondary Division delivers an intensive academic curriculum aligned with the National Examinations Board and Curriculum Development Centre (CDC), gearing students towards outstanding performance in the Secondary Education Examination (SEE).</p>
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-mortarboard me-2"></i>SEE Focus & High Academic Standards</h6>
                                <p class="small text-muted mb-0">Structured chapter-wise test series, regular revision sessions, pre-SEE board examinations, and past board question analysis.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-cpu me-2"></i>Equipped Labs & Practical Mastery</h6>
                                <p class="small text-muted mb-0">Full-fledged Physics, Chemistry, Biology, and Computer Science laboratories ensuring 100% completion of practical coursework.</p>
                            </div>
                        </div>
                    </div>
                </div>',
                'curriculum' => '<div class="curriculum-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-collection me-2"></i>SEE Curriculum Framework (CDC Nepal Guidelines)</h5>
                    <p class="text-muted">The curriculum comprises 5 compulsory core subjects and 2 elective courses totaling 7 core papers evaluated under the National Letter Grading System:</p>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr><th>Subject Title</th><th>Code / Nature</th><th>Credit Hours</th><th>Evaluation Structure</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><strong>Compulsory English</strong></td><td>Core</td><td>4 CH</td><td>75% Theory + 25% Internal Practical (Listening/Speaking)</td></tr>
                                <tr><td><strong>Compulsory Nepali (नेपाली)</strong></td><td>Core</td><td>4 CH</td><td>75% Theory + 25% Internal Practical</td></tr>
                                <tr><td><strong>Compulsory Mathematics (गणित)</strong></td><td>Core</td><td>4 CH</td><td>75% Theory + 25% Internal Practical / Projects</td></tr>
                                <tr><td><strong>Science and Technology (विज्ञान तथा प्रविधि)</strong></td><td>Core</td><td>4 CH</td><td>75% External Board + 25% Practical Lab Exam</td></tr>
                                <tr><td><strong>Social Studies (सामाजिक अध्ययन)</strong></td><td>Core</td><td>4 CH</td><td>75% External Board + 25% Community Project</td></tr>
                                <tr class="table-primary"><td colspan="4"><strong>Elective Subject Combinations (Optional I & II)</strong></td></tr>
                                <tr><td><strong>Optional I:</strong> Optional Mathematics OR Economics</td><td>Elective</td><td>4 CH</td><td>75% Theory + 25% Internal Assessment</td></tr>
                                <tr><td><strong>Optional II:</strong> Computer Science OR Accountancy</td><td>Elective</td><td>4 CH</td><td>50% Theory + 50% Practical / Lab (Computer)</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>',
                'rules' => '<div class="rules-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-exclamation-octagon me-2"></i>Academic Regulations & Board Requirements</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>Mandatory 75% Attendance:</strong> NEB regulations strictly require a minimum 75% school attendance during Grade 9 and Grade 10 to fill the SEE Examination Registration Form.</li>
                        <li class="list-group-item"><strong>Continuous Assessment & Unit Tests:</strong> Participation in all weekly tests, terminal examinations, and Pre-SEE model examinations is mandatory.</li>
                        <li class="list-group-item"><strong>Laboratory Discipline:</strong> Students must maintain certified lab record books in Science and Computer Science. Failure to submit lab journals forfeits practical scores.</li>
                        <li class="list-group-item"><strong>Zero Tolerance for Indiscipline:</strong> Possession of contraband, aggressive behavior, vandalism, or unauthorized departure from campus leads to immediate suspension.</li>
                    </ul>
                </div>',
                'admission_procedure' => '<div class="admission-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-card-checklist me-2"></i>Admission Requirements for Grade 9</h5>
                    <p class="text-muted">Admissions to Grade 9 are open to students who have successfully cleared the Grade 8 Basic Level Examination (BLE):</p>
                    <h6>Eligibility Criteria:</h6>
                    <ul class="text-muted mb-3">
                        <li>Minimum aggregate GPA of 2.0 (Grade C) in Grade 8 BLE Examination.</li>
                        <li>Passing score in Bless Itahari Secondary Entrance Assessment (English, Mathematics, and Science).</li>
                    </ul>
                    <h6>Required Documents:</h6>
                    <ul class="text-muted">
                        <li>Original Grade 8 BLE Grade Sheet and Certificate issued by the Municipality</li>
                        <li>Original Transfer Certificate (TC) signed by the Education Officer</li>
                        <li>BLE Character Certificate from the previous institution</li>
                        <li>Certified photocopy of Birth Registration Certificate (जन्मदर्ता)</li>
                        <li>4 recent passport-size photographs in formal attire</li>
                    </ul>
                </div>',
            ],
            [
                'name' => 'Higher Secondary (+2 Science / NEB)',
                'slug' => 'higher-secondary-plus-two-science-neb',
                'academic_level' => 'Higher Secondary (+2)',
                'grade_span' => 'Grades 11 and 12 (NEB Affiliated)',
                'duration' => '2 Academic Years (Annual Session)',
                'semester' => 'Annual (NEB Centralized Board Exam)',
                'requirement' => 'Passed SEE / Equivalent with minimum GPA 2.0 (C+ in Science, Math & English)',
                'evaluation_system' => 'National Examinations Board (NEB) Grading System: 75% External Theory Exam + 25% Internal Lab/Practical Evaluation',
                'starting_time' => '06:30 AM',
                'closing_time' => '01:00 PM',
                'image' => 'backend/images/courses/default.jpg',
                'gallery' => '[]',
                'status' => 1,
                'description' => 'Affiliated with the National Examinations Board (NEB), Sanothimi, Bhaktapur. Designed for aspiring doctors, engineers, IT specialists, and researchers with advanced Physics, Chemistry, Biology, and Computer Science laboratories.',
                'fulldescription' => '<div class="school-level-overview">
                    <p class="lead">The +2 Science Program at Bless Itahari is a prestigious two-year higher secondary program affiliated with the National Examinations Board (NEB), Nepal. Tailored for students aspiring to pursue careers in Medicine (MBBS/BDS), Engineering (IOE), Pure Sciences, Information Technology, and Biotechnology, our program merges conceptual curriculum with extensive practical mastery and competitive entrance orientation.</p>
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-flask me-2"></i>Advanced Scientific Labs</h6>
                                <p class="small text-muted mb-0">Separate, modern laboratories for Physics, Chemistry, Biology, and Computer Science equipped with digital microscopes, analytical balances, and safety apparatus.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-trophy me-2"></i>Medical & Engineering Entrance Coaching</h6>
                                <p class="small text-muted mb-0">Integrated preparation sessions for CEE (Common Entrance Examination) for medical sciences and IOE entrance for engineering disciplines.</p>
                            </div>
                        </div>
                    </div>
                </div>',
                'curriculum' => '<div class="curriculum-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-diagram-3 me-2"></i>NEB +2 Science Course Structure</h5>
                    <p class="text-muted">Students can opt for the <strong>Physical Group</strong> (Engineering/IT focus) or the <strong>Biological Group</strong> (Medical/Bio focus):</p>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-header bg-primary text-white fw-bold">Grade 11 Subject Cluster</div>
                                <div class="card-body">
                                    <ul class="mb-0 small">
                                        <li><strong>Compulsory English</strong> (Eng. 003) — 4 Credit Hours</li>
                                        <li><strong>Compulsory Nepali</strong> (Nep. 001) — 3 Credit Hours</li>
                                        <li><strong>Physics</strong> (Phy. 101) — Theory (75%) + Lab Practical (25%)</li>
                                        <li><strong>Chemistry</strong> (Chem. 101) — Theory (75%) + Lab Practical (25%)</li>
                                        <li><strong>Mathematics</strong> (Mat. 101) — 5 Credit Hours</li>
                                        <li><strong>Biology</strong> (Bio. 101) <em>OR</em> <strong>Computer Science</strong> (Com. 101)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-header bg-primary text-white fw-bold">Grade 12 Subject Cluster</div>
                                <div class="card-body">
                                    <ul class="mb-0 small">
                                        <li><strong>Compulsory English</strong> (Eng. 004) — 4 Credit Hours</li>
                                        <li><strong>Compulsory Nepali</strong> (Nep. 002) — 3 Credit Hours</li>
                                        <li><strong>Physics</strong> (Phy. 102) — Theory (75%) + Lab Practical (25%)</li>
                                        <li><strong>Chemistry</strong> (Chem. 102) — Theory (75%) + Lab Practical (25%)</li>
                                        <li><strong>Mathematics</strong> (Mat. 102) <em>OR</em> <strong>Biology</strong> (Bio. 102)</li>
                                        <li><strong>Computer Science</strong> (Com. 102) / Optional Science Subject</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>',
                'rules' => '<div class="rules-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-file-earmark-lock me-2"></i>Academic Regulations for +2 Science</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>NEB Attendance Mandate:</strong> Minimum 80% classroom and laboratory attendance is mandatory. Students falling short will not be recommended for NEB Board Examinations.</li>
                        <li class="list-group-item"><strong>Laboratory Safety & Attire:</strong> White lab coat, safety goggles, and adherence to lab manual guidelines are compulsory during experimental sessions.</li>
                        <li class="list-group-item"><strong>Term Examinations:</strong> First Terminal, Mid-Terminal, and Pre-Board Examinations are compulsory. Practical files must be submitted on time.</li>
                        <li class="list-group-item"><strong>Campus Discipline:</strong> Mobile phone usage during class hours is prohibited. Formal college uniform and hair grooming standards must be adhered to.</li>
                    </ul>
                </div>',
                'admission_procedure' => '<div class="admission-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-mortarboard-fill me-2"></i>Admission Process for +2 Science</h5>
                    <h6>Eligibility Criteria:</h6>
                    <ul class="text-muted mb-3">
                        <li>Minimum aggregate GPA 2.0 (Grade C+) in SEE or equivalent national/international board examination.</li>
                        <li>Minimum Grade C+ in Compulsory Mathematics, Compulsory Science, and English.</li>
                    </ul>
                    <h6>Admission Steps:</h6>
                    <ol class="text-muted mb-4">
                        <li>Fill and submit the online application or visit the Bless Itahari Admissions Office.</li>
                        <li>Appear for the competitive Written Entrance Test covering Physics, Chemistry, Mathematics, Biology, and English.</li>
                        <li>Personal counselling and interview with senior faculty members.</li>
                        <li>Final selection and enrollment confirmation upon document verification.</li>
                    </ol>
                    <h6>Scholarships:</h6>
                    <p class="text-muted small">Merit-based scholarships available for SEE GPA 3.6+, SEE District Toppers, underprivileged/remote area quotas, and sports achievers.</p>
                </div>',
            ],
            [
                'name' => 'Higher Secondary (+2 Management / NEB)',
                'slug' => 'higher-secondary-plus-two-management-neb',
                'academic_level' => 'Higher Secondary (+2)',
                'grade_span' => 'Grades 11 and 12 (NEB Affiliated)',
                'duration' => '2 Academic Years (Annual Session)',
                'semester' => 'Annual (NEB Centralized Board Exam)',
                'requirement' => 'Passed SEE / Equivalent with minimum GPA 1.6 (Grade D+) with pass marks in Math and English',
                'evaluation_system' => 'National Examinations Board (NEB) Grading System: 75% External Theory Exam + 25% Internal Project & Practical Evaluation',
                'starting_time' => '06:30 AM',
                'closing_time' => '11:30 AM',
                'image' => 'backend/images/courses/default.jpg',
                'gallery' => '[]',
                'status' => 1,
                'description' => 'A dynamic, career-oriented +2 Management program affiliated with NEB Nepal. Prepares future chartered accountants, business leaders, bankers, and entrepreneurs through accounting practicals, economics seminars, and business case studies.',
                'fulldescription' => '<div class="school-level-overview">
                    <p class="lead">The +2 Management Program at Bless Itahari delivers a contemporary, practical business education affiliated with the National Examinations Board (NEB), Nepal. Crafted for ambitious students targeting future careers in Chartered Accountancy (CA), Banking, Business Administration (BBA/BBS), Hotel Management, and Entrepreneurship.</p>
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-briefcase me-2"></i>Industry & Case-Study Orientation</h6>
                                <p class="small text-muted mb-0">Practical bookkeeping, accounting software workshops (Tally/Busy), business quizzes, bank visits, and stock market simulation exercises.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-primary"><i class="bi bi-globe2 me-2"></i>Global Business Foundation</h6>
                                <p class="small text-muted mb-0">Strong focus on managerial economics, financial literacy, communication skills, and digital commerce.</p>
                            </div>
                        </div>
                    </div>
                </div>',
                'curriculum' => '<div class="curriculum-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-calculator me-2"></i>NEB +2 Management Course Structure</h5>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-header bg-success text-white fw-bold">Grade 11 Subject Cluster</div>
                                <div class="card-body">
                                    <ul class="mb-0 small">
                                        <li><strong>Compulsory English</strong> (Eng. 003) — 4 Credit Hours</li>
                                        <li><strong>Compulsory Nepali</strong> (Nep. 001) — 3 Credit Hours</li>
                                        <li><strong>Compulsory Social Studies & Life Skills</strong> OR <strong>Mathematics</strong></li>
                                        <li><strong>Principles of Accounting I</strong> (Acc. 103) — 75% Theory + 25% Practical</li>
                                        <li><strong>Economics I</strong> (Eco. 103) — Micro & Macro Economic Basics</li>
                                        <li><strong>Business Studies I</strong> (Bus. 103) <em>OR</em> <strong>Computer Science</strong> / <strong>Hotel Management</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-header bg-success text-white fw-bold">Grade 12 Subject Cluster</div>
                                <div class="card-body">
                                    <ul class="mb-0 small">
                                        <li><strong>Compulsory English</strong> (Eng. 004) — 4 Credit Hours</li>
                                        <li><strong>Compulsory Nepali</strong> (Nep. 002) — 3 Credit Hours</li>
                                        <li><strong>Compulsory Social Studies & Life Skills</strong> OR <strong>Mathematics</strong></li>
                                        <li><strong>Principles of Accounting II</strong> (Acc. 104) — Company Accounts, Costing & Financial Analysis</li>
                                        <li><strong>Economics II</strong> (Eco. 104) — Nepalese Economy, Public Finance & Trade</li>
                                        <li><strong>Business Studies II</strong> (Bus. 104) <em>OR</em> <strong>Marketing</strong> / <strong>Hotel Management</strong> / <strong>Computer Science</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>',
                'rules' => '<div class="rules-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-card-text me-2"></i>Management Division Code of Conduct</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>Attendance:</strong> Minimum 80% attendance in lectures and practical computer lab / accounting accounting sessions is strictly enforced.</li>
                        <li class="list-group-item"><strong>Dress Code:</strong> Formal management college uniform (formal blazers, neckties, polished shoes) reflecting executive professionalism.</li>
                        <li class="list-group-item"><strong>Projects & Presentations:</strong> Completion and presentation of individual and group business case studies is mandatory for internal evaluation marks.</li>
                        <li class="list-group-item"><strong>Examination Policy:</strong> Internal terminal examinations carry significant weight in the NEB practical evaluation dossier.</li>
                    </ul>
                </div>',
                'admission_procedure' => '<div class="admission-content">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-door-open me-2"></i>Admission Guidelines for +2 Management</h5>
                    <h6>Eligibility Criteria:</h6>
                    <ul class="text-muted mb-3">
                        <li>Minimum aggregate GPA of 1.6 (Grade D+) in SEE or equivalent examination.</li>
                        <li>Passing grades in Compulsory Mathematics and English.</li>
                    </ul>
                    <h6>Admission Process:</h6>
                    <ol class="text-muted mb-4">
                        <li>Submission of online or paper application form with recent SEE marksheet copy.</li>
                        <li>Written Aptitude Assessment (English, Basic Mathematics, General Awareness).</li>
                        <li>Personal interview and career path orientation with the Management Department Faculty.</li>
                        <li>Admission confirmation and fee clearance.</li>
                    </ol>
                    <h6>Required Documents:</h6>
                    <ul class="text-muted">
                        <li>SEE Grade Sheet (Online/Official Copy)</li>
                        <li>SEE Character Certificate</li>
                        <li>Admit Card copy</li>
                        <li>Municipal Birth Registration / Citizenship Certificate</li>
                        <li>4 passport-size photographs</li>
                    </ul>
                </div>',
            ],
        ];

        // Replace old records or update appropriately
        Course::truncate();

        foreach ($levels as $level) {
            Course::create($level);
        }
    }
}
