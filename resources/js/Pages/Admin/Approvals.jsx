import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Approvals({ auth, pendingUsers }) {
    const { post, delete: destroy, processing } = useForm();

    const approveUser = (id) => {
        if (confirm('Are you sure you want to approve this user?')) {
            post(route('approvals.approve', id));
        }
    };

    const rejectUser = (id) => {
        if (confirm('Are you sure you want to reject and delete this user?')) {
            destroy(route('approvals.reject', id));
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Pending Approvals</h2>}
        >
            <Head title="Pending Approvals" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {pendingUsers.length === 0 ? (
                                <p>No pending approvals.</p>
                            ) : (
                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID (Student/Emp)</th>
                                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {pendingUsers.map((user) => (
                                                <tr key={user.id}>
                                                    <td className="px-6 py-4 whitespace-nowrap">{user.name}</td>
                                                    <td className="px-6 py-4 whitespace-nowrap">{user.email}</td>
                                                    <td className="px-6 py-4 whitespace-nowrap capitalize">{user.role}</td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        {user.role === 'student' ? user.student_id : user.employee_id}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap space-x-2">
                                                        <button
                                                            onClick={() => approveUser(user.id)}
                                                            className="text-green-600 hover:text-green-900 font-bold"
                                                            disabled={processing}
                                                        >
                                                            Approve
                                                        </button>
                                                        <button
                                                            onClick={() => rejectUser(user.id)}
                                                            className="text-red-600 hover:text-red-900 font-bold"
                                                            disabled={processing}
                                                        >
                                                            Reject
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
