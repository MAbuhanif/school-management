# Entity Relationship Diagram

```mermaid
erDiagram
    User ||--o| Student : "has profile"
    User ||--o| Teacher : "has profile"
    
    ClassRoom ||--o{ Student : "contains"
    ClassRoom ||--o{ Course : "hosts"
    
    Subject ||--o{ Course : "defines"
    
    Teacher ||--o{ Course : "teaches"
    
    Course ||--o{ Enrollment : "has"
    Student ||--o{ Enrollment : "enrolls in"
    
    Course ||--o{ Attendance : "records"
    Student ||--o{ Attendance : "attends"
    
    Course ||--o{ Grade : "evaluates"
    Student ||--o{ Grade : "receives"
    
    Student ||--o{ Fee : "owes"
    Fee ||--o{ Payment : "receives"

    User {
        bigInteger id PK
        string name
        string email
        string password
        enum role
    }

    Student {
        bigInteger id PK
        bigInteger user_id FK
        date dob
        string phone
    }

    Teacher {
        bigInteger id PK
        bigInteger user_id FK
        string qualification
        string phone
    }

    ClassRoom {
        bigInteger id PK
        string name
        string section
    }

    Subject {
        bigInteger id PK
        string name
        string code
    }

    Course {
        bigInteger id PK
        bigInteger class_room_id FK
        bigInteger teacher_id FK
        bigInteger subject_id FK
        string name
    }

    Enrollment {
        bigInteger id PK
        bigInteger student_id FK
        bigInteger course_id FK
        timestamp enrolled_at
    }

    Attendance {
        bigInteger id PK
        bigInteger student_id FK
        bigInteger course_id FK
        date date
        enum status
    }

    Grade {
        bigInteger id PK
        bigInteger student_id FK
        bigInteger course_id FK
        string assessment_name
        decimal score
    }

    Fee {
        bigInteger id PK
        bigInteger student_id FK
        decimal amount
        date due_date
        enum status
    }

    Payment {
        bigInteger id PK
        bigInteger fee_id FK
        decimal amount
        timestamp paid_at
    }

    Notice {
        bigInteger id PK
        string title
        text content
        enum target_role
    }
```

# Migration Plan

1.  **Users Table**: Already exists (default Laravel). Add `role` column.
2.  **ClassRooms & Subjects**: Create these independent tables first.
3.  **Students & Teachers**: Create these, linking to `users`. `students` may link to `class_rooms`.
4.  **Courses**: Create, linking to `class_rooms`, `teachers`, and `subjects`.
5.  **Enrollments**: Pivot/Link table for `students` and `courses`.
6.  **Academic Records**: Create `attendances` and `grades`, linking to `students` and `courses`.
7.  **Financials**: Create `fees` (linked to `students`) and `payments` (linked to `fees`).
8.  **Notices**: Independent table.
