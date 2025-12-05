import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react'; // Import router
import Table from '@/Components/Table';
import Pagination from '@/Components/Pagination';
import Input from '@/Components/Input';
import Select from '@/Components/Select';
import { useState, useEffect } from 'react';
import { useToast } from '@/Components/Toast'; // Assuming we want toast feedback

export default function Index({ auth, students, filters, classRooms, sort_by, sort_dir }) {
    const { addToast } = useToast();
    const [search, setSearch] = useState(filters.search || '');
    const [classId, setClassId] = useState(filters.class_room_id || '');
    const [selectedIds, setSelectedIds] = useState([]);

    // Debounce search
    useEffect(() => {
        const timer = setTimeout(() => {
            if (search !== (filters.search || '')) {
                router.get(
                    route('students.index'),
                    { search, class_room_id: classId, status: filters.status, sort_by, sort_dir },
                    { preserveState: true, replace: true }
                );
            }
        }, 300);
        return () => clearTimeout(timer);
    }, [search]);

    const handleFilterChange = (key, value) => {
        if (key === 'class_room_id') setClassId(value);
        router.get(
            route('students.index'),
            { search, class_room_id: key === 'class_room_id' ? value : classId, status: filters.status, sort_by, sort_dir },
            { preserveState: true, replace: true }
        );
    };

    const handleSort = (column) => {
        const newDir = sort_by === column && sort_dir === 'asc' ? 'desc' : 'asc';
        router.get(
             route('students.index'),
             { search, class_room_id: classId, sort_by: column, sort_dir: newDir },
             { preserveState: true }
        );
    };

    const handleSelectAll = (e) => {
        if (e.target.checked) {
            setSelectedIds(students.data.map(s => s.id));
        } else {
            setSelectedIds([]);
        }
    };

    const handleSelectRow = (id) => {
        if (selectedIds.includes(id)) {
            setSelectedIds(selectedIds.filter(sid => sid !== id));
        } else {
            setSelectedIds([...selectedIds, id]);
        }
    };

    const handleBulkDelete = () => {
        if (!confirm('Are you sure you want to delete selected students?')) return;
        router.post(route('students.bulk-destroy'), { ids: selectedIds }, {
            onSuccess: () => {
                setSelectedIds([]);
                addToast('Selected students deleted successfully', 'success');
            }
        });
    };

    const handleExport = () => {
        // Trigger export
        window.location.href = route('students.export');
    };

    const columns = [
        {
            header: <input type="checkbox" onChange={handleSelectAll} checked={selectedIds.length === students.data.length && students.data.length > 0} />,
            accessor: 'id',
            render: (value, row) => (
                <input
                    type="checkbox"
                    checked={selectedIds.includes(row.id)}
                    onChange={() => handleSelectRow(row.id)}
                    onClick={(e) => e.stopPropagation()}
                />
            )
        },
        { header: 'Name', accessor: 'user.name' }, // Accessor for sorting might need check if Table supports nested
        { header: 'Email', accessor: 'user.email' },
        { header: 'Class', accessor: 'class_room.name', render: (val, row) => row.class_room?.name || 'N/A' },
        {
            header: 'Actions', accessor: 'id', render: (val, row) => (
                <Link href={route('students.edit', row.id)} className="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
            )
        }
    ];

    // For sorting, we might need to adjust Table component or handle click on header
    // Ideally Table component accepts onHeaderClick or similar. 
    // Since current Table component looks simple, I will wrap headers in clickable spans inside columns definition for now or update Table component. 
    // Checking Table.jsx it renders header as string. I will modify columns definition to include sortable headers logic manually or just assume Table needs update.
    // Given the Table component source: 
    // {col.header}
    // So passing a React Element works.

    const sortableHeader = (label, column) => (
        <button onClick={() => handleSort(column)} className="flex items-center font-bold uppercase">
            {label}
            {sort_by === column && (
                <span className="ml-1">{sort_dir === 'asc' ? '↑' : '↓'}</span>
            )}
        </button>
    );

    const tableColumns = [
        columns[0], // Checkbox
        { header: sortableHeader('Name', 'name'), accessor: 'user.name', render: (_, r) => r.user.name },
        { header: sortableHeader('Email', 'email'), accessor: 'user.email', render: (_, r) => r.user.email },
        { header: sortableHeader('Class', 'class_room_id'), accessor: 'class_room.name', render: (val, row) => row.class_room?.name || 'N/A' },
        columns[4] // Actions
    ];


    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Students</h2>}
        >
            <Head title="Students" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-visible shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <div className="flex flex-col md:flex-row justify-between mb-6 gap-4">
                                <div className="flex gap-4 flex-1">
                                    <Input
                                        id="search"
                                        placeholder="Search..."
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                        className="mb-0 w-full md:w-1/3"
                                    />
                                    <Select
                                        id="class_room"
                                        options={[{ value: '', label: 'All Classes' }, ...classRooms.map(c => ({ value: c.id, label: c.name }))]}
                                        value={classId}
                                        onChange={(e) => handleFilterChange('class_room_id', e.target.value)}
                                        className="mb-0 w-full md:w-1/3"
                                    />
                                </div>
                                <div className="flex gap-2">
                                     {selectedIds.length > 0 && (
                                        <button
                                            onClick={handleBulkDelete}
                                            className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Delete ({selectedIds.length})
                                        </button>
                                     )}
                                     <button
                                        onClick={handleExport}
                                        className="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                     >
                                        Export CSV
                                     </button>
                                    {auth.can.create_student && (
                                        <Link
                                            href={route('students.create')}
                                            className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Add Student
                                        </Link>
                                    )}
                                </div>
                            </div>
                            
                            <Table
                                columns={[
                                    columns[0], // Checkbox - Consider hiding if no bulk actions?
                                    { header: sortableHeader('Name', 'name'), accessor: 'user.name', render: (_, r) => r.user.name },
                                    { header: sortableHeader('Email', 'email'), accessor: 'user.email', render: (_, r) => r.user.email },
                                    { header: sortableHeader('Class', 'class_room_id'), accessor: 'class_room.name', render: (val, row) => row.class_room?.name || 'N/A' },
                                    {
                                        header: 'Actions', accessor: 'id', render: (val, row) => (
                                            <>
                                                {row.can.edit && (
                                                    <Link href={route('students.edit', row.id)} className="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                                )}
                                            </>
                                        )
                                    }
                                ]}
                                data={students.data}
                            />
                            
                            <div className="mt-4">
                                <Pagination links={students.meta.links} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
