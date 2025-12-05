import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';
import Input from '@/Components/Input';
import { useToast } from '@/Components/Toast';
import { useEffect } from 'react';

export default function Create({ auth }) {
    const { addToast } = useToast();
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('teachers.store'), {
            onSuccess: () => {
                addToast('Teacher created successfully!', 'success');
                reset();
            },
            onError: () => {
                 addToast('Failed to create teacher.', 'error');
            },
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Add Teacher</h2>}
        >
            <Head title="Add Teacher" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit}>
                                <Input
                                    id="name"
                                    label="Name"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    error={errors.name}
                                    required
                                    className="block w-full"
                                />

                                <Input
                                    id="email"
                                    type="email"
                                    label="Email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    error={errors.email}
                                    required
                                    className="block w-full mt-4"
                                />

                                {/* Add more fields as needed */}

                                <div className="flex items-center justify-end mt-4">
                                    <PrimaryButton className="ms-4" disabled={processing}>
                                        Save Teacher
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
