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
<body style="font-family: Calibri; margin-left: 150px; margin-top: 50px">
    <div class="demo-info">  
        <div class="demo-tip icon-tip"></div>  
    </div>  
    <div style="margin-left:50px 0;"></div>  
    <div class="easyui-layout" style="width:1000px;height:585px;border:none;">  
        <div data-options="region:'north'" style="height:75px;border:none;" ><img src="../images/logo.png" /></div>  
        <div data-options="region:'south',split:true" style="height:50px;"></div>    
        <div data-options="region:'west',split:true" title="Menu" style="width:230px;padding:10px;background-color:#DAECF5">
            <h1 style="line-height: 2px"> Admin </h1>
            <h3> Nama Organisasi </h3>
            <table style="font-size: 25px; margin: 5px; padding: 5px;">
                <tr><td><a href ="index.php">Pengaturan</a></td></tr>
                <tr><td><a href ="divisi.php">Divisi/Biro</a></td></tr>
                <tr><td><a href ="field_general.php">Form Pendaftaran</a></td></tr>
                <tr><td>Slot Wawancara</td></tr>
            </table>
        </div>  
        <div data-options="region:'center',title:'Divisi/Biro',iconCls:'icon-ok'">  
            <div style="margin:10px 10;">
    
    <table id="dg" class="easyui-datagrid" style="width:700px;height:250px"  
            url="get_users.php"  
            toolbar="#toolbar" pagination="true"  
            rownumbers="true" fitColumns="true" singleSelect="true">  
        <thead>  
            <tr>  
                <th field="nama" width="100">Nama</th>  
                <th field="PJ" width="100">Berlaku untuk</th>  
            </tr>  
        </thead>  
    </table>  
    <div id="toolbar">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Edit</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Remove</a>  
    </div>  
      
    <div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"  
            closed="true" buttons="#dlg-buttons">  
        <div class="ftitle">Information</div>  
        <form id="fm" method="post" novalidate>  
            <div class="fitem">  
                <label>Nama:</label>  
                <input name="firstname" class="easyui-validatebox" required="true">  
            </div>  
            <div class="fitem">  
                <label>berlaku :</label>
                <!-- ke input gak ya datanya?? -->
                <select name="berlaku">
                <option value="all">Semua Divisi</option>
                </select>
                <!--<input name="lastname" class="easyui-validatebox" required="true">-->  
            </div>  
        </form>  
    </div>  
    <div id="dlg-buttons">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()">Save</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
    </div>  
    <script type="text/javascript">  
        var url;  
        function newUser(){  
            $('#dlg').dialog('open').dialog('setTitle','New User');  
            $('#fm').form('clear');  
            url = 'save_user.php';  
        }  
        function editUser(){  
            var row = $('#dg').datagrid('getSelected');  
            if (row){  
                $('#dlg').dialog('open').dialog('setTitle','Edit User');  
                $('#fm').form('load',row);  
                url = 'update_user.php?id='+row.id;  
            }  
        }  
        function saveUser(){  
            $('#fm').form('submit',{  
                url: url,  
                onSubmit: function(){  
                    return $(this).form('validate');  
                },  
                success: function(result){  
                    var result = eval('('+result+')');  
                    if (result.errorMsg){  
                        $.messager.show({  
                            title: 'Error',  
                            msg: result.errorMsg  
                        });  
                    } else {  
                        $('#dlg').dialog('close');      // close the dialog  
                        $('#dg').datagrid('reload');    // reload the user data  
                    }  
                }  
            });  
        }  
        function destroyUser(){  
            var row = $('#dg').datagrid('getSelected');  
            if (row){  
                $.messager.confirm('Confirm','Are you sure you want to destroy this user?',function(r){  
                    if (r){  
                        $.post('destroy_user.php',{id:row.id},function(result){  
                            if (result.success){  
                                $('#dg').datagrid('reload');    // reload the user data  
                            } else {  
                                $.messager.show({   // show error message  
                                    title: 'Error',  
                                    msg: result.errorMsg  
                                });  
                            }  
                        },'json');  
                    }  
                });  
            }  
        }  
    </script>  
    <style type="text/css">  
        #fm{  
            margin:0;  
            padding:10px 30px;  
        }  
        .ftitle{  
            font-size:14px;  
            font-weight:bold;  
            padding:5px 0;  
            margin-bottom:10px;  
            border-bottom:1px solid #ccc;  
        }  
        .fitem{  
            margin-bottom:5px;  
        }  
        .fitem label{  
            display:inline-block;  
            width:80px;  
        }  
    </style>  
</body>  
</html>  