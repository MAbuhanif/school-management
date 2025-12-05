import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Edit({ auth, student, classRooms }) {
    const { data, setData, put, processing, errors } = useForm({
        name: student.user.name,
        email: student.user.email,
        dob: student.dob,
        gender: student.gender,
        address: student.address,
        phone: student.phone,
        class_room_id: student.class_room_id,
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('students.update', student.id));
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
                                    <div className="mb-4">
                                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="name">Name</label>
                                        <input id="name" type="text" value={data.name} onChange={(e) => setData('name', e.target.value)} className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                                        {errors.name && <div className="text-red-500 text-xs italic">{errors.name}</div>}
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="email">Email</label>
                                        <input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                                        {errors.email && <div className="text-red-500 text-xs italic">{errors.email}</div>}
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="dob">Date of Birth</label>
                                        <input id="dob" type="date" value={data.dob} onChange={(e) => setData('dob', e.target.value)} className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                                        {errors.dob && <div className="text-red-500 text-xs italic">{errors.dob}</div>}
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="gender">Gender</label>
                                        <select id="gender" value={data.gender} onChange={(e) => setData('gender', e.target.value)} className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                        {errors.gender && <div className="text-red-500 text-xs italic">{errors.gender}</div>}
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="phone">Phone</label>
                                        <input id="phone" type="text" value={data.phone} onChange={(e) => setData('phone', e.target.value)} className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                                        {errors.phone && <div className="text-red-500 text-xs italic">{errors.phone}</div>}
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="class_room_id">Class</label>
                                        <select id="class_room_id" value={data.class_room_id} onChange={(e) => setData('class_room_id', e.target.value)} className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="">Select Class</option>
                                            {classRooms.map((room) => (
                                                <option key={room.id} value={room.id}>{room.name}</option>
                                            ))}
                                        </select>
                                        {errors.class_room_id && <div className="text-red-500 text-xs italic">{errors.class_room_id}</div>}
                                    </div>

                                    <div className="mb-4 col-span-2">
                                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="address">Address</label>
                                        <textarea id="address" value={data.address} onChange={(e) => setData('address', e.target.value)} className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                                        {errors.address && <div className="text-red-500 text-xs italic">{errors.address}</div>}
                                    </div>
                                </div>

                                <div className="flex items-center justify-end mt-4">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    >
                                        Update Student
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
