import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import Input from '@/Components/Input';
import Select from '@/Components/Select';
import DatePicker from '@/Components/DatePicker';
import FileUploader from '@/Components/FileUploader';
import PrimaryButton from '@/Components/PrimaryButton';
import { useToast } from '@/Components/Toast';

export default function Create({ auth, classRooms }) {
    const { addToast } = useToast();
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        dob: '',
        gender: '',
        address: '',
        phone: '',
        class_room_id: '',
        profile_picture: null,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('students.store'), {
            onSuccess: () => {
                addToast('Student created successfully!', 'success');
                reset();
            },
            onError: () => {
                addToast('Failed to create student. Please check the form.', 'error');
            }
        });
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Add Student</h2>}
        >
            <Head title="Add Student" />

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
                                    <Input
                                        id="password"
                                        type="password"
                                        label="Password"
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                        error={errors.password}
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
                                    <FileUploader
                                        id="profile_picture"
                                        label="Profile Picture"
                                        accept="image/*"
                                        onChange={(files) => setData('profile_picture', files[0])}
                                        error={errors.profile_picture}
                                    />
                                    <div className="col-span-1 md:col-span-2">
                                        <Input
                                            id="address"
                                            label="Address"
                                            value={data.address}
                                            onChange={(e) => setData('address', e.target.value)}
                                            error={errors.address}
                                            required // Assuming input can handle generic props, else might need Textarea component
                                        />
                                    </div>
                                </div>

                                <div className="flex items-center justify-end mt-4">
                                    <PrimaryButton className="ms-4" disabled={processing}>
                                        Create Student
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
