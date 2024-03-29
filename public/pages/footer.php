<?php

/**
 * This is the default footer for pages of the College Recruitment Application
 * Students will be able to enter their information and have it sent to the database.
 * Uses the student class to create a new student object
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/18/2023
 *
 * @package RYM2
 * Filename: footer.php
 * @version 1.0.0
 * @requires PHP 8.1.2+
 * @requires Bootstrap 5.3.2+
 * @requires Font Awesome 6.4.2+
 * @requires jQuery 3.7.1+
 */
/* Setup HTML for page footer */
?>
</div>
</body>
<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Patrick Barnhardt 2023</div>
            <div>
                <a href="<?php echo APP_URL . '/admin/dashboard.php'; ?>">Admin Login</a>
                &middot;
                <a href="<?php echo APP_URL . '/index.php?path=privacy'; ?>">Privacy Policy</a>
                &middot;
                <a href="<?php echo APP_URL . '/index.php?path=terms'; ?>">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
    <?php echo includeFooter(); ?>
</footer>
<!-- Select2 script -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2-major').select2({
            dropdownParent: $('#majorsParent'),
            tags: true
        });
        $('.select2-degree').select2({
            dropdownParent: $('#degreeParent'),
        });
        $('.select2-school').select2({
            dropdownParent: $('#schoolParent'),
        });
        $('.select2-aoi').select2({
            dropdownParent: $('#aoiParent'),
        });
    });
</script>

</html>
