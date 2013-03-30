

<html>
    <head>  
    <meta charset="UTF-8">  
    <title>Oprecx</title>  
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.3.2/themes/default/easyui.css">  
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.3.2/themes/icon.css">  
    <link rel="stylesheet" type="text/css" href="../demo.css">  
    <script type="text/javascript" src="jquery-easyui-1.3.2/jquery-1.8.0.min.js"></script>  
    <script type="text/javascript" src="jquery-easyui-1.3.2/jquery.easyui.min.js"></script>  
</head>  
<body>  
    <div class="demo-info">  
        <div class="demo-tip icon-tip"></div>  
    </div>  
    <div style="margin:10px 0;"></div>  
    <div class="easyui-layout" style="width:1330px;height:585px;">  
        <div data-options="region:'north'" style="height:50px;" ></div>  
        <div data-options="region:'south',split:true" style="height:50px;"></div>    
        <div data-options="region:'west',split:true" title="Menu" style="width:230px;padding:10px;">
            <table style="padding-left:10px;margin:10px;font-size: 25px">
                <tr><td><a href ="index.php">Pengaturan Umum</a></td></tr>
                <tr><td><a href ="divisi.php">Divisi/Biro</a></td></tr>
                <tr><td>Form Pendaftaran</td></tr>
                <tr><td>Slot Wawancara</td></tr>
            </table>
        </div>  
        <div data-options="region:'center',title:'Divisi/Biro',iconCls:'icon-ok'">  
            <div style="margin:10px 0;">
                <table border="1" >
                    <thead>
                        <th>No</th>
                        <th>Divisi</th>
                        <th>PJ</th>
                        <th>Kuota</th>
                    </thead>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                        <a href="javascript:void(0)" style="font-size: 20px;"class="easyui-linkbutton" onclick="edit()">Edit</a>
                        <a href="javascript:void(0)" style="font-size: 20px;"class="easyui-linkbutton" onclick="">Hapus</a>
            </div>
        </div>  
        </div>  
  
</body>  
</html>

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
