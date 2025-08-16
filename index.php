<?php 
    session_start();

    class todo{
        public $id;
        public $judul;
        public $status;

        public function __construct($id, $judul, $status = "pending") {
            $this->id = $id;
            $this->judul = $judul;
            $this->status = $status;
        }
    }

    class listtodo {
        private $tasks = [];
    
        public function __construct() {
            if (!isset($_SESSION['tasks'])) {
                $_SESSION['tasks'] = [];
            }
            $this->tasks = $_SESSION['tasks'];
        }
    
        public function addTask($judul, $status) {
            $id = uniqid();
            $this->tasks[$id] = new todo($id, $judul, $status);
            $this->save();
        }
    
        public function editTask($id, $judul, $status) {
            if (isset($this->tasks[$id])) {
                $this->tasks[$id]->judul = $judul;
                $this->tasks[$id]->status = $status;
                $this->save();
            }
        }
    
        public function deleteTask($id) {
            if (isset($this->tasks[$id])) {
                unset($this->tasks[$id]);
                $this->save();
            }
        }
    
        public function getTasks() {
            return $this->tasks;
        }
    
        private function save() {
            $_SESSION['tasks'] = $this->tasks;
        }
    }
    
    $todoList = new listtodo();
    
    if (isset($_POST['add'])) {
        $todoList->addTask($_POST['judul'], $_POST['status']);
        header("Location: index.php");
        exit;
    }
    
    if (isset($_POST['edit'])) {
        $status = isset($_POST['status']) ? $_POST['status'] : $_POST['status_hidden'];
        $todoList->editTask($_POST['id'], $_POST['judul'], $status);
        header("Location: index.php");
        exit;
    }
    
    if (isset($_GET['delete'])) {
        $todoList->deleteTask($_GET['delete']);
        header("Location: index.php");
        exit;
    }
    
    $tasks = $todoList->getTasks();
?>

<!DOCTYPE html>
<html>
<head>
    <title>LSP | Tri Mur - 0618104010</title>
    <style>
        table { border-collapse: collapse; width: 60%; margin-bottom: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background-color: #ddd; }
    </style>
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-5.3.7-dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-6 mx-auto mt-3">
                <h1>Aplikasi To-Do List Sederhana</h1>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-6">
                <form id="formAdd" action="" method="post" class="border border-1 border-secondary p-4 rounded-3">
                    <h3 class="border-bottom mb-3">Form Tambah Data</h3>
                    <div class="row">
                        <div class="col-2">
                            <label>Judul </label>
                        </div>
                        <div class="col-10">
                            <input class="form-control" type="text" name="judul">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-2">
                            <label>Status </label>
                        </div>
                        <div class="col-10">
                            <select class="form-select" name="status">
                                <option selected disable>Pilih Status</option>
                                <option value="belum">belum</option>
                                <option value="selesai">selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-2"></div>
                        <div class="col-10">
                            <button type="submit" name="add" id="add" class="btn btn-primary">Tambah Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-4 p-3">
            <div class="col border border-1 border-secondary p-4 rounded-3">
                <h3 class="border-bottom mb-3">
                    List Data
                    <!-- <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                        reload
                    </button> -->
                </h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>#ID</td>
                            <td>Judul</td>
                            <td>Status</td>
                            <td>Opsi</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tasks as $data){ ?>
                            <tr>
                                <td>
                                    <form id="formedit" action="" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $data->id ?>">
                                        <input type="hidden" name="judul" value="<?= $data->judul ?>">

                                        <input type="checkbox" name="status"
                                            value="selesai"
                                            onchange="this.form.submit()"
                                            <?= $data->status == "selesai" ? "checked" : "" ?>
                                        >

                                        <input type="hidden" name="status_hidden" value="belum">

                                        <input type="hidden" name="edit" value="1">
                                    </form>
                                    <?=$data->id?>
                                </td>
                                <td><?=$data->judul?></td>
                                <td><?=$data->status?></td>
                                <td><a class="btn btn-danger" href="index.php?delete=<?= $data->id ?>" onclick="return confirm('Hapus task ini?')">Hapus</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("formAdd").addEventListener("submit", function(e) {
            const status = document.querySelector("select[name='status']").value;
            const judul = document.querySelector("input[name='judul']").value;

            if (status === "Pilih Status" || status === "" || status === "disable" || judul == "") {
                e.preventDefault(); // stop submit
                alert("judul dan Status tidak boleh kosong!");
                return false;
            } else {
                alert("Data berhasil diperbarui!");
                location.reload();
            }
        });
</script>
</body>
</html>