<script src="/assets/bootstrap/js/bootstrap.bundle.js"></script>
<script src="/assets/jquery/jquery.min.js"></script>
<?php
if ($parts[0] == "measurements") { ?>
    <script src="/assets/plotly/plotly.min.js"></script>
<?php }
if (file_exists("./assets/js/$parts[0].js")) { ?>
    <script src="/assets/js/<?php echo $parts[0]; ?>.js"></script>
<?php }
?>
</body>
</html>