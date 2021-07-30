   <footer class="bg-dark text-center text-lg-start" style="margin-top:20px;">
      <!-- Copyright -->
      <div class="text-center p-3 text-white">
        ITS - Copyright &copy; 2020 - <?php echo date('Y');?> | <small>Developed by: <a href="https://www.linkedin.com/in/ivan-funcik-dev/" target="__blank" style="color:#4287f5 !important;">Ivan Funćik</a></small>
        <!-- Rounded switch -->
        <small class="float-right">&nbsp; <b>Dark mode</b></small>
         <label class="switch float-right dark-mode">
           <input type="checkbox" onchange="mod()">
           <span class="slider round"></span>
         </label>
      </div>
      <!-- Copyright -->
    </footer>
  </body>
</html>

<!-- BACK BUTTON -->
<script>
function goBack() {
  window.history.back();
}
</script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- CUSTOM JS -->
<script src="JS/dark_mode.js"></script>
<script src="JS/hidePassword.js"></script>
<script src="JS/collapsedNavbar.js"></script>