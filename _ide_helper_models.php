<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $assignment_id
 * @property string $cst_id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $due_date
 * @property int $max_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClassSubjectTeacher $classSubjectTeacher
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grade> $grades
 * @property-read int|null $grades_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentSubmission> $submissions
 * @property-read int|null $submissions_count
 * @method static \Database\Factories\AssignmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereCstId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereUpdatedAt($value)
 */
	class Assignment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $assignment_id
 * @property int $students_id
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon|null $submitted_at
 * @property int|null $score
 * @property string|null $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Assignment $assignment
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereStudentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereUpdatedAt($value)
 */
	class AssignmentSubmission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $enrollment_id
 * @property int $students_id
 * @property string $cst_id
 * @property string $academic_year
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $enrolled_at
 * @property \Illuminate\Support\Carbon|null $left_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClassSubjectTeacher $classSubjectTeacher
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment whereAcademicYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment whereCstId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment whereEnrolledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment whereEnrollmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment whereLeftAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment whereStudentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassEnrollment whereUpdatedAt($value)
 */
	class ClassEnrollment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $cst_id
 * @property int $class_id
 * @property string $subject_code
 * @property int $teacher_id
 * @property string $academic_year
 * @property string $semester
 * @property string|null $join_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassEnrollment> $enrollments
 * @property-read int|null $enrollments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exam> $exams
 * @property-read int|null $exams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $joinedStudents
 * @property-read int|null $joined_students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Material> $materials
 * @property-read int|null $materials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \App\Models\SchoolClass $schoolClass
 * @property-read \App\Models\Subject $subject
 * @property-read \App\Models\Teacher $teacher
 * @method static \Database\Factories\ClassSubjectTeacherFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher whereAcademicYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher whereCstId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher whereJoinCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher whereSubjectCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSubjectTeacher whereUpdatedAt($value)
 */
	class ClassSubjectTeacher extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $exam_id
 * @property string $cst_id
 * @property string $title
 * @property string $exam_type
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property int $duration_minutes
 * @property float $pass_score
 * @property bool $shuffle_questions
 * @property bool $shuffle_options
 * @property bool $is_published
 * @property string|null $instructions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\ClassSubjectTeacher $classSubjectTeacher
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamQuestion> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamSession> $sessions
 * @property-read int|null $sessions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCstId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereExamType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam wherePassScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereShuffleOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereShuffleQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereUpdatedAt($value)
 */
	class Exam extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $exam_answer_id
 * @property int $exam_id
 * @property int $students_id
 * @property int $question_id
 * @property array<array-key, mixed>|null $answer
 * @property string|null $answer_file
 * @property bool|null $is_correct
 * @property float|null $score
 * @property int|null $graded_by
 * @property \Illuminate\Support\Carbon|null $graded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Exam $exam
 * @property-read \App\Models\Teacher|null $grader
 * @property-read \App\Models\ExamQuestion $question
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereAnswerFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereExamAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereGradedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereGradedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereStudentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereUpdatedAt($value)
 */
	class ExamAnswer extends \Eloquent {}
}

namespace App\Models{
/**
 * Format options / correct_answer (JSON) menurut tipe:
 *  pilihan_ganda          options: ["A teks","B teks",.
 *
 * ..]            correct: 1 indeks (mis. 2)
 *  pilihan_ganda_kompleks options: [...]                              correct: [0,2]
 *  benar_salah            options: ["pernyataan 1","pernyataan 2"]    correct: [true,false]
 *  menjodohkan            options: {"left":[...],"right":[...]}       correct: {"0":1,"1":0}
 *  esai                   -                                           correct: null (tanpa bobot)
 *
 * @property int $exam_question_id
 * @property int $exam_id
 * @property string $question_text
 * @property string $question_type
 * @property array<array-key, mixed>|null $options
 * @property string|null $image_path
 * @property array<array-key, mixed>|null $correct_answer
 * @property float $score_weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Exam $exam
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereExamQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereQuestionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereScoreWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereUpdatedAt($value)
 */
	class ExamQuestion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $exam_session_id
 * @property int $exam_id
 * @property int $students_id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property \Illuminate\Support\Carbon|null $left_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Exam $exam
 * @property-read \App\Models\Student $student
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamViolation> $violations
 * @property-read int|null $violations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereExamSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereLeftAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereStudentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamSession whereUpdatedAt($value)
 */
	class ExamSession extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $exam_violation_id
 * @property int $exam_session_id
 * @property int $students_id
 * @property string $type
 * @property string|null $detail
 * @property \Illuminate\Support\Carbon $occurred_at
 * @property-read \App\Models\ExamSession $session
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamViolation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamViolation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamViolation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamViolation whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamViolation whereExamSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamViolation whereExamViolationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamViolation whereOccurredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamViolation whereStudentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamViolation whereType($value)
 */
	class ExamViolation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $grade_id
 * @property int $students_id
 * @property string $cst_id
 * @property string $grade_type
 * @property int|null $assignment_id
 * @property int|null $exam_id
 * @property float|null $score
 * @property int|null $graded_by
 * @property \Illuminate\Support\Carbon|null $graded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Assignment|null $assignment
 * @property-read \App\Models\ClassSubjectTeacher $classSubjectTeacher
 * @property-read \App\Models\Exam|null $exam
 * @property-read \App\Models\Teacher|null $grader
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereCstId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereGradeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereGradedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereGradedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereStudentsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grade whereUpdatedAt($value)
 */
	class Grade extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $material_id
 * @property string $cst_id
 * @property string $title
 * @property string|null $description
 * @property string|null $file_path
 * @property string $uploaded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClassSubjectTeacher $classSubjectTeacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCstId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUploadedAt($value)
 */
	class Material extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $notification_id
 * @property int $user_id
 * @property string $title
 * @property string $message
 * @property bool $is_read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $cst_id
 * @property string $day
 * @property string $start_time
 * @property string $end_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClassSubjectTeacher $classSubjectTeacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereCstId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereUpdatedAt($value)
 */
	class Schedule extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $class_id
 * @property string $name
 * @property int|null $homeroom_teacher_id
 * @property string $academic_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassSubjectTeacher> $classSubjectTeachers
 * @property-read int|null $class_subject_teachers_count
 * @property-read \App\Models\Teacher|null $homeroomTeacher
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Database\Factories\SchoolClassFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereAcademicYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereHomeroomTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereUpdatedAt($value)
 */
	class SchoolClass extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $nis
 * @property string|null $nisn
 * @property int|null $class_id
 * @property string|null $pob
 * @property \Illuminate\Support\Carbon|null $dob
 * @property string|null $address
 * @property string|null $district
 * @property string|null $city
 * @property string|null $province
 * @property string|null $parent_name
 * @property string|null $parent_phone
 * @property int|null $entry_year
 * @property string $status
 * @property string $gender
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassEnrollment> $classEnrollments
 * @property-read int|null $class_enrollments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamAnswer> $examAnswers
 * @property-read int|null $exam_answers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamSession> $examSessions
 * @property-read int|null $exam_sessions_count
 * @property-read string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grade> $grades
 * @property-read int|null $grades_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassSubjectTeacher> $joinedClasses
 * @property-read int|null $joined_classes_count
 * @property-read \App\Models\SchoolClass|null $schoolClass
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentSubmission> $submissions
 * @property-read int|null $submissions_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamViolation> $violations
 * @property-read int|null $violations_count
 * @method static \Database\Factories\StudentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereEntryYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereParentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereParentPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student wherePob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUpdatedAt($value)
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassSubjectTeacher> $classSubjectTeachers
 * @property-read int|null $class_subject_teachers_count
 * @method static \Database\Factories\SubjectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereUpdatedAt($value)
 */
	class Subject extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $nip
 * @property string|null $specialization
 * @property string|null $education_level
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $hire_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassSubjectTeacher> $classSubjectTeachers
 * @property-read int|null $class_subject_teachers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SchoolClass> $homeroomClasses
 * @property-read int|null $homeroom_classes_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\TeacherFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereEducationLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereHireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher whereUpdatedAt($value)
 */
	class Teacher extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $phone
 * @property string|null $photo
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Student|null $student
 * @property-read \App\Models\Teacher|null $teacher
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserId($value)
 */
	class User extends \Eloquent {}
}

