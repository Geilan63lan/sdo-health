## ADDED Requirements

### Requirement: Health records filtered by user school
The system SHALL filter health records based on user's assigned school.

#### Scenario: Health Coordinator sees only their school's records
- **WHEN** a health coordinator views health records
- **THEN** system shows only records from their assigned school

#### Scenario: Principal sees only their school's records
- **WHEN** a principal views health records
- **THEN** system shows only records from their assigned school

#### Scenario: SDO Admin sees all health records
- **WHEN** an SDO admin views health records
- **THEN** system shows records from all schools

### Requirement: Vaccinations filtered by user school
The system SHALL filter vaccinations based on user's assigned school.

#### Scenario: Health Coordinator sees only their school's vaccinations
- **WHEN** a health coordinator views vaccinations
- **THEN** system shows only vaccinations from their assigned school

### Requirement: Absences filtered by user school
The system SHALL filter absences based on user's assigned school.

#### Scenario: Health Coordinator sees only their school's absences
- **WHEN** a health coordinator views absences
- **THEN** system shows only absences from their assigned school

### Requirement: Students filtered by user school
The system SHALL filter students based on user's assigned school.

#### Scenario: Principal sees only their school's students
- **WHEN** a principal views students
- **THEN** system shows only students from their assigned school
