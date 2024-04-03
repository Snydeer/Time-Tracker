<?php
    include_once 'header.php';
?>
<?php
    if (isset($_SESSION["useruid"])) {
        $current = $_SESSION["username"];
    } else {
        $current = $_SESSION["employeename"];
    }

?>

    <section class="Picker">
        <h2>Welcome, <?php echo $current; ?>!</h2>
     </section>

     <div class="buttons">

        <?php
                if (isset($_SESSION["useruid"])) {
                    echo '
                        <button class="main" onclick="navigateToPwChange()">
                            <span>Change Password</span>
                        </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToChangePay()">
                            <span>View Employee Pay</span>
                        </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToSettings()">
                            <span>Settings</span>
                        </button>
                    ';
                } elseif (isset($_SESSION["employeeuid"])) {
                    echo '
                        <button class="main" onclick="navigateToPwChange()">
                            <span>Change Password</span>
                         </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToTimeSheet()">
                            <span>Payroll</span>
                         </button>
                    ';
                    echo '
                        <button class="main" onclick="navigateToSettings()">
                            <span>Settings</span>
                         </button>
                    ';
                } else {
                    header("location: ../index.php");
                }
            ?>

<?php
    include_once 'footer.php';
?>