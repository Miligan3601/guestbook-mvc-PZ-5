<?php

namespace guestbook\Controllers;

class GuestbookController
{
    public function execute()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $aConfig = require_once 'config.php';

        $infoMessage = '';

        if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['text'])) {
            $aComment = $_POST;
            $aComment['date'] = date('m.d.Y');

            $db = new \PDO("mysql:host={$aConfig['host']};dbname={$aConfig['name']}", $aConfig['user'], $aConfig['pass']);
            $stmt = $db->prepare("INSERT INTO comments (email, name, text, date) VALUES (:email, :name, :text, :date)");
            $stmt->execute([
                ':email' => $aComment['email'],
                ':name' => $aComment['name'],
                ':text' => $aComment['text'],
                ':date' => $aComment['date'],
            ]);
        } elseif (!empty($_POST)) {
            $infoMessage = 'Заповнити поля форми!';
        }


        $db = new \PDO("mysql:host={$aConfig['host']};dbname={$aConfig['name']}", $aConfig['user'], $aConfig['pass']);
        $query = $db->query("SELECT * FROM comments");
        $aComments = $query->fetchAll(\PDO::FETCH_ASSOC);


        $this->renderView([
            'infoMessage' => $infoMessage,
            'aComments' => $aComments
        ]);
    }

    public function renderView($arguments = [])
    {
        extract($arguments);
        require_once 'ViewSections/sectionHead.php';
        require_once 'ViewSections/sectionNavbar.php';
        ?>

        <div class="container">
            <br>

            <div class="card card-primary">
                <div class="card-header bg-primary text-light">Guestbook form</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <form method="post" class="fw-bold">
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter name">
                                </div>
                                <div class="form-group">
                                    <label>Text</label>
                                    <textarea name="text" class="form-control" placeholder="Enter text" required></textarea>
                                </div><br>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Send">
                                </div>
                            </form>
                            <br>
                            <?php
                            if (!empty($infoMessage)) {
                                echo "<span style='color:red'>$infoMessage</span>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="card card-primary">
                <div class="card-header bg-body-secondary text-dark">Comments</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?php
                            foreach ($aComments as $comment) {
                                echo $comment['name'] . '<br>';
                                echo $comment['email'] . '<br>';
                                echo $comment['text'] . '<br>';
                                echo $comment['date'] . '<br>';
                                echo '<hr>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <?php
    }
}
