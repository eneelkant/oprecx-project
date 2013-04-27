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
        <div style="margin-left: 50px"></div>
        <div class="easyui-layout" style="width:1000px; height:1000px; border: none;">
            <div data-options="region:'north'" style="height:80px; border: none;">
                <img src="../images/logo.png" />
            </div>
            <div data-options="region:'south'" style="height:50px;">
                <h4 style="text-align: center"> Faculty Of Computer Science </h4>
            </div>
            <div data-options="region:'west',split:true" title="OprecX Admin" style="width:250px; padding:10px; background-color:#DAECF5;">
                <h1 style="line-height: 2px"> Admin </h1>
                <h3> Nama Organisasi </h3>
                    <table style="font-size: 25px; margin: 5px; padding: 5px;">
                        <tr> <td style="border: 1px solid white; padding: 5px;"> <a href="index.php"> Pengaturan </a> </td> </tr>
                        <tr> <td style="border: 1px solid white; padding: 5px;"> <a href="divisi.php"> Divisi/Biro </a> </td> </tr>
                        <tr> <td style="border: 1px solid white; padding: 5px;"> <a href="#"> Field </a> </td> </tr>
                        <tr> <td style="border: 1px solid white; padding: 5px;"> <a href="#"> Slot Wawancara </a> </td> </tr>
                        <tr> <td style="border: 1px solid white; padding: 5px;"> <a href="#"> Upload Tugas </a> </td> </tr>
                        <tr> <td style="border: 1px solid white; padding: 5px;"> <a href="#"> Pengumuman </a> </td> </tr>
                    </table>
            </div>
            <div data-options="region:'center',title:'Pengaturan',iconCls:'icon-ok'">
                <div style="margin:10px 0;"></div>
                <div style="padding:10px 0 10px 60px">
                    <form id="ff" method="post">
                        <h1> Pengaturan Umum </h1>
                        <table style="font-size: 20px">
                            <tr>
                                <td>Nama Organisasi * </td>
                                <td><input style='width:350px' class="easyui-validatebox" type="text" name="name" data-options="required:true"></input></td>
                            </tr>
                            <tr>
                                <td>Nama Acara * </td>
                                <td><input style='width:350px' class="easyui-validatebox" type="text" name="name" data-options="required:true"></input></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top">Deskripsi </td>
                                <td><textarea style='width:350px; height:100px;' class="message" style="height:100px;" type="text" name="subject" ></textarea></td>
                            </tr>
                            <tr>
                                <td>Alamat Web</td>
                                <td><input style='width:350px' name="easyui-validatebox"  data-options="validType:'url'"></input></td>
                            </tr>
                            <tr>
                                <td>Alamat email * </td>
                                <td><input style='width:350px' class="easyui-validatebox" type="text" name="email" data-options="required:true,validType:'email'"></input></td>
                            </tr>
                        </table>
                        <h1> Pengaturan Pendaftaran </h1>
                        <table style="font-size: 20px">
                            <tr>
                                <td> Waktu Pendaftaran *</td>
                                <td><input style='width:225px' class="easyui-validatebox" type="text" name="name" data-options="required:true"></input></td>
                            </tr>
                            <tr>
                                <td> Jumlah maksimum </td>
                                <td><input style='width:225px' class="easyui-validatebox" type="text" name="name"></input></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top"> Izinkan mendaftar dengan</td>
                                <td>
                                    <input type="checkbox" name="Account" value="FB"> Facebook <br />
                                    <input type="checkbox" name="Account" value="twitter"> Twitter <br />
                                    <input type="checkbox" name="Account" value="google+"> Google+ <br />
                                    <input type="checkbox" name="Account" value="email"> Email <br />
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top"> Range pendaftar </td>
                                <td>
                                    <input type="radio" name="pendaftar" value="fakultas"> Mahasiswa Fakultas : 
                                    <select name="fakultas">
                                        <option value="Fasilkom"> Fakultas Ilmu Komputer </option>
                                        <option value="FK"> Fakultas Kedokteran </option>
                                        <option value="FKG"> Fakultas Kedokteran Gigi </option>
                                        <option value="FT"> Fakultas Teknik </option>
                                        <option value="FMIPA"> Fakultas Matematika dan Ilmu Pengetahuan Alam </option>
                                        <option value="FPsi"> Fakultas Psikologi </option>
                                        <option value="FF"> Fakultas Farmasi </option>
                                        <option value="FKM"> Fakultas Kesehatan Masyarakat </option>
                                        <option value="FIK"> Fakultas Ilmu Keperawatan </option>
                                        <option value="FISIP"> Fakultas Ilmu Sosial dan Ilmu Politik </option>
                                        <option value="FIB"> Fakultas Ilmu Budaya </option>
                                        <option value="FH"> Fakultas Hukum </option>
                                    </select>
                                    <br />
                                    <input type="radio" name="pendaftar" value="ui"> Mahasiswa UI <br />
                                    <input type="radio" name="pendaftar" value="umum+"> Umum <br />
                                </td>
                            </tr>
                        </table>
                    </form>
                    <a href="javascript:void(0)" style="font-size: 20px;"class="easyui-linkbutton" onclick="submitForm()"> Simpan </a>
                </div>
                <script>
                        function submitForm() {
                            $('#ff').form('submit');
                        }
                </script>
            </div>
        </div>
    </body>
</html>
