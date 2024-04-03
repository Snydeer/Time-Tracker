CREATE TRIGGER `new_employee_trigger` AFTER INSERT ON `employee`
 FOR EACH ROW BEGIN
    INSERT INTO payroll (employees_uid, employees_company, pay_amount)
    VALUES (NEW.employees_uid, NEW.employees_company, 0);
END
