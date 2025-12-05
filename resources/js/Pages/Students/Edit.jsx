import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, router } from '@inertiajs/react'; // Import router for manual post if needed but useForm post with _method put is easier
import Input from '@/Components/Input';
import Select from '@/Components/Select';
import DatePicker from '@/Components/DatePicker';
import FileUploader from '@/Components/FileUploader';
import PrimaryButton from '@/Components/PrimaryButton';
import { useToast } from '@/Components/Toast';

export default function Edit({ auth, student, classRooms }) {
    const { addToast } = useToast();
    const { data, setData, post, processing, errors } = useForm({
        _method: 'put', // Spoof PUT for file upload
        name: student.user.name,
        email: student.user.email,
        dob: student.dob,
        gender: student.gender,
        address: student.address,
        phone: student.phone,
        class_room_id: student.class_room_id,
        profile_picture: null,
    });

    const submit = (e) => {
        e.preventDefault();
        // Use post because we are sending a file, but spoof PUT
        post(route('students.update', student.id), {
             onSuccess: () => {
                addToast('Student updated successfully!', 'success');
             },
             onError: () => {
                addToast('Failed to update student.', 'error');
             }
        });
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Edit Student</h2>}
        >
            <Head title="Edit Student" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit}>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <Input
                                        id="name"
                                        label="Name"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        error={errors.name}
                                        required
                                    />
                                    <Input
                                        id="email"
                                        type="email"
                                        label="Email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        error={errors.email}
                                        required
                                    />
                                    <DatePicker
                                        id="dob"
                                        label="Date of Birth"
                                        value={data.dob}
                                        onChange={(e) => setData('dob', e.target.value)}
                                        error={errors.dob}
                                        required
                                    />
                                    <Select
                                        id="gender"
                                        label="Gender"
                                        options={[
                                            { value: 'male', label: 'Male' },
                                            { value: 'female', label: 'Female' },
                                            { value: 'other', label: 'Other' },
                                        ]}
                                        value={data.gender}
                                        onChange={(e) => setData('gender', e.target.value)}
                                        error={errors.gender}
                                        required
                                    />
                                    <Input
                                        id="phone"
                                        label="Phone"
                                        value={data.phone}
                                        onChange={(e) => setData('phone', e.target.value)}
                                        error={errors.phone}
                                        required
                                    />
                                    <Select
                                        id="class_room_id"
                                        label="Class"
                                        options={classRooms.map(room => ({ value: room.id, label: room.name }))}
                                        value={data.class_room_id}
                                        onChange={(e) => setData('class_room_id', e.target.value)}
                                        error={errors.class_room_id}
                                        required
                                    />
                                    
                                     <div className="col-span-1 md:col-span-2">
                                        {student.profile_picture && (
                                            <div className="mb-2">
                                                <p className="text-sm font-medium text-gray-700 mb-1">Current Picture:</p>
                                                <img 
                                                    src={`/storage/${student.profile_picture}`} 
                                                    alt="Current Profile" 
                                                    className="w-20 h-20 object-cover rounded-full border"
                                                />
                                            </div>
                                        )}
                                        <FileUploader
                                            id="profile_picture"
                                            label="Change Profile Picture"
                                            accept="image/*"
                                            onChange={(files) => setData('profile_picture', files[0])}
                                            error={errors.profile_picture}
                                        />
                                    </div>

                                    <div className="col-span-1 md:col-span-2">
                                        <Input
                                            id="address"
                                            label="Address"
                                            value={data.address}
                                            onChange={(e) => setData('address', e.target.value)}
                                            error={errors.address}
                                            required
                                        />
                                    </div>
                                </div>

                                <div className="flex items-center justify-end mt-4">
                                    <PrimaryButton className="ms-4" disabled={processing}>
                                        Update Student
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
