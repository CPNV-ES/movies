$("#loadfiles").click(function(){
    $.ajax({

       url: 'php/script/scan_files.php',
       type: 'POST',
       data:
       {
           "path": $("#mon-fichier").val()
       }
   }
);
});
