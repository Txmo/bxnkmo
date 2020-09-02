<?php

require_once BXNMKO . '/Filter/Group.php';

use Filter\Group;

$groups = Group::all();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Groups</title>
    <link rel="stylesheet" href="../../css/materialize.min.css">
    <script type="text/javascript" src="../../js/Group.js"></script>
    <script type="text/javascript" src="../../js/Helper.js"></script>
    <script type="text/javascript" src="../../js/Ajax.js"></script>
</head>
<body>
<nav class="row">

</nav>
<div id="main" class="row">
    <aside class="col s3 grey">

    </aside>
    <main class="col s9">
        <div class="row">
            <div class="col s9">
                Your Groups
            </div>
            <button onclick="Helper.toggle('idAddNewGroup')" type="button" class="btn col s3 center-align black-text">
                New Group
            </button>
        </div>
        <div id="idAddNewGroup" class="row hide">
            <form onkeydown="return event.key !== 'Enter';" id="idAddNewGroupForm" action="create.php" method="post">
                <input maxlength="50" required type="text" name="groupName" value="" placeholder="Group name">
                <button class="btn" onclick="Ajax.sendForm('idAddNewGroupForm', Group.callbackForAdd, true)"
                        type="button">Add
                </button>
            </form>
        </div>
        <div class="row">
            <table>
                <thead>
                <tr class="row">
                    <th class="col s7">Name</th>
                    <th class="col s5">Action</th>
                </tr>
                </thead>
                <tbody id="idTableGroupBody">
                <?php foreach ($groups as $key => $group) { ?>
                    <tr id="idGroupRow<?= (int)$group->id; ?>">
                        <td class="col s7"><?= e($group->name); ?></td>
                        <td class="col s5 row">
                            <a href="edit.php?groupId=<?= (int)$group->id; ?>" class="btn col s6">
                                Edit
                            </a>
                            <button onclick="Group.delete('<?= (int)$group->id ?>')" class="btn col s6"
                                    type="button">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<footer class="row"></footer>

<script type="text/javascript" src="../../js/materialize.min.js"></script>
</body>
</html>
