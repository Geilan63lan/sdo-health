## ADDED Requirements

### Requirement: SDO Admin has full access
The system SHALL grant SDO Admins access to all schools and all data.

#### Scenario: SDO Admin sees all schools
- **WHEN** an SDO Admin views the Schools resource
- **THEN** system displays all schools regardless of category

#### Scenario: SDO Admin sees all health records
- **WHEN** an SDO Admin views Health Records
- **THEN** system displays records from all schools

### Requirement: Health Coordinators have school-restricted access
The system SHALL restrict Health Coordinators to data from their assigned school only.

#### Scenario: Health Coordinator sees only their school
- **WHEN** a Health Coordinator views Schools resource
- **THEN** system displays only their assigned school

#### Scenario: Health Coordinator sees only their school's records
- **WHEN** a Health Coordinator views Health Records
- **THEN** system displays only records from their assigned school

### Requirement: Principals have school-restricted access
The system SHALL restrict Principals to data from their assigned school only.

#### Scenario: Principal sees only their school
- **WHEN** a Principal views Schools resource
- **THEN** system displays only their assigned school

#### Scenario: Principal sees only their school's records
- **WHEN** a Principal views Health Records
- **THEN** system displays only records from their assigned school

### Requirement: Users cannot access other schools' data
The system SHALL prevent users from viewing or editing data from schools they're not assigned to.

#### Scenario: Health Coordinator cannot view other school
- **WHEN** a Health Coordinator tries to access another school's URL
- **THEN** system returns 403 Forbidden

#### Scenario: Principal cannot view other school's records
- **WHEN** a Principal tries to access another school's health records
- **THEN** system returns 403 Forbidden
