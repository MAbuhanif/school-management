```javascript
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, router } from '@inertiajs/react';
import Select from '@/Components/Select';
import Input from '@/Components/Input';
import PrimaryButton from '@/Components/PrimaryButton';
import { useState, useEffect } from 'react';
import { useToast } from '@/Components/Toast';

export default function Index({ auth, classRooms, courses, students, filters }) {
    const { addToast } = useToast();
    const [values, setValues] = useState({
        class_room_id: filters.class_room_id || '',
        course_id: filters.course_id || '',
        assessment_name: filters.assessment_name || '',
    });

    const { data, setData, post, processing, errors, reset } = useForm({
        course_id: filters.course_id || '',
        assessment_name: filters.assessment_name || '',
        max_score: '100',
        grades: [],
    });

    // Initialize grades when students load
    useEffect(() => {
        if (students.length > 0) {
            setData(prev => ({
                ...prev,
                grades: students.map(s => ({
                    student_id: s.id,
                    score: s.current_grade !== null ? s.current_grade : ''
                }))
            }));
        }
    }, [students]);

    // Keep form data in sync with filters logic (a bit tricky if we want to filter AND edit)
    // Actually, usually you filter first, THEN you get a list to edit.
    // If I change filters, I should probably reload the page.

    const handleFilterChange = (field, value) => {
        const newValues = { ...values, [field]: value };
        setValues(newValues);
        
        // Reload to fetch students
        router.get(route('grades.index'), newValues, {
            preserveState: true,
            preserveScroll: true,
            only: ['students', 'filters'],
        });
        
        // Also update form data mostly for course and assessment info
        if (field === 'course_id') setData('course_id', value);
        if (field === 'assessment_name') setData('assessment_name', value);
    };

    const handleGradeChange = (studentId, score) => {
        const newGrades = data.grades.map(g => 
            g.student_id === studentId ? { ...g, score: score } : g
        );
        setData('grades', newGrades);
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('grades.store'), {
            onSuccess: () => {
                addToast('Grades saved successfully!', 'success');
            },
            onError: () => {
                addToast('Failed to save grades. Check inputs.', 'error');
            }
        });
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Grade Entry</h2>}
        >
            <Head title="Grade Entry" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            
                            <div className="flex flex-col md:flex-row gap-4 mb-6 border-b pb-6">
                                <Select
                                    id="class_room_id"
                                    label="Class"
                                    options={classRooms.map(r => ({ value: r.id, label: r.name }))}
                                    value={values.class_room_id}
                                    onChange={(e) => handleFilterChange('class_room_id', e.target.value)}
                                    className="w-full md:w-1/3"
                                />
                                <Select
                                    id="course_id"
                                    label="Course"
                                    options={courses.map(c => ({ value: c.id, label: c.title }))}
                                    value={values.course_id}
                                    onChange={(e) => handleFilterChange('course_id', e.target.value)}
                                    className="w-full md:w-1/3"
                                />
                                <Input
                                    id="assessment_name"
                                    label="Assessment Name"
                                    value={values.assessment_name}
                                    onChange={(e) => handleFilterChange('assessment_name', e.target.value)}
                                    className="w-full md:w-1/3"
                                    placeholder="e.g. Midterm Exam"
                                />
                            </div>

                            {values.class_room_id && values.course_id && values.assessment_name && students.length > 0 ? (
                                <form onSubmit={submit}>
                                    <div className="mb-4">
                                         <label className="block text-gray-700 text-sm font-bold mb-2">Max Score</label>
                                         <input 
                                            type="number" 
                                            value={data.max_score} 
                                            onChange={(e) => setData('max_score', e.target.value)}
                                            className="shadow appearance-none border rounded w-32 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                         />
                                    </div>

                                    <div className="overflow-x-auto">
                                        <table className="min-w-full bg-white border border-gray-200">
                                            <thead>
                                                <tr>
                                                    <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Student
                                                    </th>
                                                    <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Current Score
                                                    </th>
                                                    <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Entry
                                                    </th>
                                                    <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                        Report Card
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {students.map((student, index) => (
                                                    <tr key={student.id}>
                                                        <td className="px-6 py-4 border-b border-gray-200">
                                                            <div className="text-sm font-medium text-gray-900">{student.user.name}</div>
                                                            <div className="text-sm text-gray-500">{student.user.email}</div>
                                                        </td>
                                                        <td className="px-6 py-4 border-b border-gray-200 text-sm text-gray-500">
                                                            {student.current_grade ?? '-'}
                                                        </td>
                                                        <td className="px-6 py-4 border-b border-gray-200">
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                max={data.max_score}
                                                                value={data.grades.find(g => g.student_id === student.id)?.score || ''}
                                                                onChange={(e) => handleGradeChange(student.id, e.target.value)}
                                                                className="shadow appearance-none border rounded w-24 py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            />
                                                        </td>
                                                         <td className="px-6 py-4 border-b border-gray-200">
                                                            <a 
                                                                href={route('grades.report-card', student.id)} 
                                                                className="text-indigo-600 hover:text-indigo-900 font-bold"
                                                                target="_blank"
                                                                rel="noreferrer"
                                                            >
                                                                Download PDF
                                                            </a>
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>

                                    <div className="mt-6 flex justify-end">
                                        <PrimaryButton disabled={processing}>
                                            Save All Grades
                                        </PrimaryButton>
                                    </div>
                                </form>
                            ) : (
                                <div className="text-center py-10 text-gray-500">
                                    Please select Class, Course, and enter an Assessment Name to view student list and enter grades.
                                </div>
                            )}

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
```
