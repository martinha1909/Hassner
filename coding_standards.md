Coding Standards

1. Naming Conventions:

    1.1 variable names should be expressed in underscore format. (i.e this_is_a_variable)

    1.2 Macros should be all caps

    1.3 Avoid mixed line endings (no \r\n, just use \n)

    1.4 Classes name should match with filename (i.e class called "Hassner" should have file Hassner.php)

    1.5 all instance, function name, class, and class constants are in mixed case with a lowercase first letter. Internal words start with capital letters.

2. Formatting:

    2.1 Indentation should all be used with tabs (no spaces)

    2.2 if else structure always needs opening and closing brackets (applies to one line if else structure)

    2.3 Opening and closing brackets and quotation marks require a separate line

    2.4 conditional statements require a space (i.e x < 4)

3. Files:

    3.1 Backend files has to end with "<filename>Backend.php" (i.e LoginBackend.php)
    
    3.2 File name has to have first word uppercase format (i.e SignupBackend.php)

    3.3 No SQL queries should be happening in the frontend files. 

    3.4 Backend files should be used as the intermediary between frontend and the database