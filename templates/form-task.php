<?php  ?>
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
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form"  action="/add.php" method="post" autocomplete="off" enctype='multipart/form-data'>
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <?= (isset($error['task']))? '<p class="form__message">'.$error['task'].'</p>' : '' ?>
            <input class="form__input" type="text" name="task" id="name" value="<?php echo isset($_POST['task'])? strip_tags($_POST['task']): '' ?>" placeholder="Введите название">
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <?= (isset($error['categories_id']))? '<p class="form__message">'.$error['categories_id'].'</p>' : '' ?>
            <select class="form__input form__input--select" name="categories_id" id="project">
                <?php foreach ($data_project as $value) : ?>
                <option value="<?= $value['id']?>"><?=$value['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>
            <?= (isset($error['date_completion']))? '<p class="form__message">'.$error['date_completion'].'</p>' : '' ?>
            <input class="form__input form__input--date" type="text" name="date_completion" id="date" value="<?php echo isset($_POST['date_completion'])? $_POST['date_completion']: '' ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
