import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Index({ auth, teachers }) {
    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Teachers</h2>}
        >
            <Head title="Teachers" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <div className="flex justify-between mb-6">
                                <h3 className="text-lg font-medium">Teachers List</h3>
                                <Link
                                    href={route('teachers.create')}
                                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Add Teacher
                                </Link>
                            </div>
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {teachers?.data?.map((teacher) => (
                                        <tr key={teacher.id}>
                                            <td className="px-6 py-4 whitespace-nowrap">{teacher.user?.name || teacher.name}</td>
                                            <td className="px-6 py-4 whitespace-nowrap">{teacher.user?.email || teacher.email}</td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <Link href={route('teachers.edit', teacher.id)} className="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                            </td>
                                        </tr>
                                    )) || (
                                        <tr>
                                            <td colSpan="3" className="px-6 py-4 text-center text-gray-500">No teachers found.</td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
