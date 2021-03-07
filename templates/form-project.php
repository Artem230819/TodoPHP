
<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($data_project as $value) : ?>
                <li class="main-navigation__list-item <?php if ((int)$_GET['id'] === $value['id']){echo 'main-navigation__list-item--active';} ?>">
                    <a class="main-navigation__list-item-link" href="/index.php?id=<?= $value['id'] ?>"><?=$value['name']?></a>
                    <span class="main-navigation__list-item-count"><?=$value['count']?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="/add-project.php">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form"  action="/add-project.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <input class="form__input" type="text" name="project" id="project_name" value="<?php echo isset($_POST['project'])? strip_tags($_POST['project']): '' ?>" placeholder="Введите название проекта">
            <?= (isset($error['project']))? '<p class="form__message">'.$error['project'].'</p>' : '' ?>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
