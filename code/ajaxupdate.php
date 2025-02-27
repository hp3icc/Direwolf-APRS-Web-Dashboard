<?php 
// This file is part of the Direwolf APRS Web Dashboard as available at https://github.com/PC7MM/Direwolf-APRS-Web-Dashboard
// Developed by Michael PC7MM and Richard PD3RFR as an extension of https://github.com/IZ7BOJ/direwolf_webstat and https://github.com/IZ7BOJ/APRS_dashboard as developed by Alfredo IZ7BOJ
// See config.php for adjustable parameters and see https://www.youtube.com/watch?v=7bMf7rWCfnE for more information

if (isset($ajaxupdatetype)) {
?>
<script>
$(function() {
        var ajaxupdate = function (a) {
                 $.get('/' + window.location.pathname.substring(1) + '?ajax&rnd=<?php echo md5(rand()); ?>', function(data) { // add random number to prevent CDN content caching
                        if (($("#refresh").length) == 0 || a ==1 || refresh.checked == 1) {
                                $('#ajaxcontent').<?php echo $ajaxupdatetype; ?>(data); // append dynamic content or replace dynamic content
                        }
                        if (scrolldown.checked == 1) { // only refresh content if refresh checkbox is turned on
                                $('html, body').animate( { scrollTop: $(document).height() },1);
                        }
                });
        };
	ajaxupdate(1); // directly display dynamic content when page is loaded
	setInterval(ajaxupdate,<?php echo($refresh); ?>); // periodically refresh dynamic content
});
</script>

<?php } ?>
