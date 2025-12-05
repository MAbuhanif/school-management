import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react'; // Import router for manual visits
import Select from '@/Components/Select';
import DatePicker from '@/Components/DatePicker';
import { useState, useEffect } from 'react';
import axios from 'axios';
import { useToast } from '@/Components/Toast';

export default function Index({ auth, classRooms, courses, students, filters }) {
    const { addToast } = useToast();
    const [values, setValues] = useState({
        class_room_id: filters.class_room_id || '',
        course_id: filters.course_id || '',
        date: filters.date || new Date().toISOString().split('T')[0],
    });
    
    // Local state for optimistic updates
    const [localStudents, setLocalStudents] = useState(students);

    useEffect(() => {
        setLocalStudents(students);
    }, [students]);

    const handleChange = (field, value) => {
        const newValues = { ...values, [field]: value };
        setValues(newValues);
        
        // Reload page with new filters
        router.get(route('attendance.index'), newValues, {
            preserveState: true,
            preserveScroll: true,
            only: ['students', 'filters'],
        });
    };

    const handleToggle = async (studentId, currentStatus) => {
        if (!values.course_id) {
            addToast('Please select a course first.', 'error');
            return;
        }

        const newStatus = currentStatus === 'present' ? 'absent' : 'present';
        
        // Optimistic Update
        setLocalStudents(prev => prev.map(s => 
            s.id === studentId ? { ...s, attendance_status: newStatus } : s
        ));

        try {
            await axios.post(route('attendance.store'), {
                student_id: studentId,
                course_id: values.course_id,
                date: values.date,
                status: newStatus,
            });
            // Success, do nothing (state is already correct)
        } catch (error) {
            // Revert on error
            setLocalStudents(prev => prev.map(s => 
                s.id === studentId ? { ...s, attendance_status: currentStatus } : s
            ));
            addToast('Failed to update attendance.', 'error');
            console.error(error);
        }
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Attendance</h2>}
        >
            <Head title="Attendance" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                           <div className="flex flex-col md:flex-row gap-4 mb-6">
                                <Select
                                    id="class_room_id"
                                    label="Class"
                                    options={classRooms.map(r => ({ value: r.id, label: r.name }))}
                                    value={values.class_room_id}
                                    onChange={(e) => handleChange('class_room_id', e.target.value)}
                                    className="w-full md:w-1/3"
                                />
                                <Select
                                    id="course_id"
                                    label="Course"
                                    options={courses.map(c => ({ value: c.id, label: c.title }))}
                                    value={values.course_id}
                                    onChange={(e) => handleChange('course_id', e.target.value)}
                                    className="w-full md:w-1/3"
                                />
                                <DatePicker
                                    id="date"
                                    label="Date"
                                    value={values.date}
                                    onChange={(e) => handleChange('date', e.target.value)}
                                    className="w-full md:w-1/3"
                                />
                           </div>

                           {!values.class_room_id && (
                               <div className="text-center py-10 text-gray-500">
                                   Please select a class to view students.
                               </div>
                           )}

                           {values.class_room_id && localStudents.length === 0 && (
                               <div className="text-center py-10 text-gray-500">
                                   No students found in this class.
                               </div>
                           )}

                           {values.class_room_id && localStudents.length > 0 && (
                               <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                   {localStudents.map(student => (
                                       <div key={student.id} className="border rounded-lg p-4 flex items-center justify-between hover:shadow-md transition-shadow">
                                           <div className="flex items-center space-x-3">
                                               {student.profile_picture ? (
                                                   <img src={`/storage/${student.profile_picture}`} alt={student.user.name} className="w-10 h-10 rounded-full object-cover" />
                                               ) : (
                                                   <div className="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                                       {student.user.name.charAt(0)}
                                                   </div>
                                               )}
                                               <div>
                                                   <p className="font-semibold text-gray-800">{student.user.name}</p>
                                                   <p className="text-xs text-gray-500">{student.user.email}</p>
                                               </div>
                                           </div>
                                           <button
                                               onClick={() => handleToggle(student.id, student.attendance_status)}
                                               className={`px-4 py-2 rounded-full text-sm font-bold transition-colors ${
                                                   student.attendance_status === 'present'
                                                       ? 'bg-green-100 text-green-800 hover:bg-green-200'
                                                       : 'bg-red-100 text-red-800 hover:bg-red-200'
                                               }`}
                                           >
                                               {student.attendance_status === 'present' ? 'Present' : 'Absent'}
                                           </button>
                                       </div>
                                   ))}
                               </div>
                           )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
