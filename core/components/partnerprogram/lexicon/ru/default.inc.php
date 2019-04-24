<?php
include_once 'setting.inc.php';

$_lang['partnerprogram'] = 'Партнерская программа';
$_lang['partnerprogram_menu_desc'] = 'Управление партнерской программой.';
$_lang['partnerprogram_intro_msg'] = 'Вы можете выделять сразу несколько элементов при помощи Shift или Ctrl.';

$lang["setting_partnerprogram_apikey_yandex"] = "Ключ API Яндекса";
$lang["setting_partnerprogram_date_format"] = "Формат даты";
$lang["setting_partnerprogram_email_manager"] = "Список email менеджера";
$lang["setting_partnerprogram_paid"] = "Сколько начислять за объект в рублях";
$lang["setting_partnerprogram_minimal_paid"] = "Минимальный порог для выплат в рублях";

$lang["partnerprogram_profile_save"] = "Сохранить";
$lang["partnerprogram_balance_updated"] = "Информация обновлена";
$lang["partnerprogram_removing_error"] = "Ошибка при удалении";
$lang["partnerprogram_removing_success"] = "Объект удален";

$lang["partnerprogram_object_found"] = "Объект найден";
$lang["partnerprogram_object_not_found"] = "Объект не найден";

$lang["partnerprogram_nonono"] = "У вас нет прав на редактирование";
$lang["partnerprogram_nonono_status"] = "Статус объекта не позволяет его редактировать";
$lang["partnerprogram_object_updated"] = "Объект обновлен";

$lang["partnerprogram_err_status_final"] = "Статус объекта финальный, смена невозможна";
$lang["partnerprogram_err_status_fixed"] = "Статус объекта зафиксирован, смена возможна только на последующие статусы";
$lang["partnerprogram_err_status_same"] = "Этот статус уже установлен";
$lang["partnerprogram_payed"] = "Сумма поставлена в очередь на выплату.";


/* ---------------- Основное ----------------------- */

$_lang['partnerprogram_menu_create'] = 'Создать';
$_lang['partnerprogram_menu_update'] = 'Обновить';
$_lang['partnerprogram_color'] = 'Цвет';
$_lang['partnerprogram_email_user'] = 'Отправлять email пользователю?';
$_lang['partnerprogram_subject_user'] = 'Тема письма пользователю';
$_lang['partnerprogram_body_user'] = 'Текст письма пользователю';
$_lang['partnerprogram_email_manager'] = 'Отправлять email менеджеру?';
$_lang['partnerprogram_subject_manager'] = 'Тема письма менеджеру';
$_lang['partnerprogram_body_manager'] = 'Текст письма менеджеру';
$_lang['partnerprogram_description'] = 'Описание';
$_lang['partnerprogram_active'] = 'Активен';
$_lang['partnerprogram_actions'] = 'Действия';
$_lang['partnerprogram_btn_create'] = 'Создать';
$_lang['partnerprogram_menu_remove_multiple_confirm'] = 'Удалить все элементы?';
$_lang['partnerprogram_menu_remove_confirm'] = 'Удалить элемент?';
$_lang['partnerprogram_name'] = 'Наименование';
$_lang['partnerprogram_menu_remove_title'] = 'Удалить';
$_lang['partnerprogram_menu_disable'] = 'Отключить';
$_lang['partnerprogram_menu_remove'] = 'Удалить';
$_lang['partnerprogram_err_ae'] = 'Произошла ошибка';
$_lang['partnerprogram_err_register_globals'] = "Параметр Register Globals включен";
$_lang['partnerprogram_err_unknown'] = "Неизвестное действие";
$_lang['partnerprogram_error_object_exist'] = "Введенный вами объект уже есть в базе данных";
$_lang['partnerprogram_object_no_exist'] = "Введенный вами объект отсутствует в базе данных";
$_lang["partnerprogram_object_data"] = "Информация по объекту";
$_lang["partnerprogram_object_add"] = "Объект успешно добавлен";

/* ---------------- Объекты ------------------------- */
$_lang['partnerprogram_objects'] = 'Объекты';
$_lang['partnerprogram_object_id'] = 'ID';
$_lang['partnerprogram_object_name'] = 'Название';
$_lang['partnerprogram_object_user_id'] = 'Пользователь';
$_lang['partnerprogram_object_description'] = 'Описание';
$_lang['partnerprogram_object_area'] = 'Площадь';
$_lang['partnerprogram_object_locality'] = 'Регион';
$_lang['partnerprogram_object_city'] = 'Город';
$_lang['partnerprogram_object_street'] = 'Улица';
$_lang['partnerprogram_object_house'] = 'Дом';
$_lang['partnerprogram_object_coordinates'] = 'Координаты';
$_lang['partnerprogram_object_customer'] = 'Компания';
$_lang['partnerprogram_object_contact_name'] = 'ФИО контакта';
$_lang['partnerprogram_object_contact_email'] = 'Контактный email';
$_lang['partnerprogram_object_contact_phone'] = 'Контактный телефон';
$_lang['partnerprogram_object_createdon'] = 'Дата создания';
$_lang['partnerprogram_object_updatedon'] = 'Дата редактирования';
$_lang['partnerprogram_object_status'] = 'Статус';
$_lang['partnerprogram_object_active'] = 'Активно';

$_lang['partnerprogram_object_create'] = 'Создать объект';
$_lang['partnerprogram_object_update'] = 'Изменить объект';
$_lang['partnerprogram_object_enable'] = 'Включить объект';
$_lang['partnerprogram_objects_enable'] = 'Включить объекты';
$_lang['partnerprogram_object_disable'] = 'Отключить объект';
$_lang['partnerprogram_objects_disable'] = 'Отключить объекты';
$_lang['partnerprogram_object_remove'] = 'Удалить объект';
$_lang['partnerprogram_objects_remove'] = 'Удалить объекты';
$_lang['partnerprogram_object_remove_confirm'] = 'Вы уверены, что хотите удалить этот объект?';
$_lang['partnerprogram_objects_remove_confirm'] = 'Вы уверены, что хотите удалить эти объекты?';
$_lang['partnerprogram_object_active'] = 'Включено';

$_lang['partnerprogram_object_err_name'] = 'Вы должны указать имя Объекта.';
$_lang['partnerprogram_object_err_ae'] = 'Объект с таким именем уже существует.';
$_lang['partnerprogram_object_err_nf'] = 'Объект не найден.';
$_lang['partnerprogram_object_err_ns'] = 'Объект не указан.';
$_lang['partnerprogram_object_err_remove'] = 'Ошибка при удалении Объекта.';
$_lang['partnerprogram_object_err_save'] = 'Ошибка при сохранении Объекта.';

$_lang['partnerprogram_grid_search'] = 'Поиск';
$_lang['partnerprogram_grid_actions'] = 'Действия';

/* --------------------- Статусы -------------- */
$_lang['partnerprogram_statuses'] = 'Статусы объекта';
$_lang['partnerprogram_statuses_intro_msg'] = 'Вы можете выделять сразу несколько элементов при помощи Shift или Ctrl.';
$_lang['partnerprogram_status_final'] = 'Итоговый статус';
$_lang['partnerprogram_status_final_help'] = 'Если статус является итоговым, его нельзя переключить на другой';
$_lang['partnerprogram_status_fixed'] = 'Зафиксировать статус';
$_lang['partnerprogram_status_fixed_help'] = 'Если статус зафиксирован, то его нельзя будет переключить на статус идущий до него';
$_lang['partnerprogram_status_id'] = 'ID';
$_lang['partnerprogram_status_name'] = 'Наименование';
$_lang['partnerprogram_status_email_user'] = 'Email пользователю';
$_lang['partnerprogram_status_email_manager'] = 'Email менеджеру';
$_lang['partnerprogram_status_rank'] = 'Позиция';


/* --------------------- Баланс -------------- */
$_lang['partnerprogram_balance'] = 'Баланс пользователей';
$_lang['partnerprogram_balance_intro_msg'] = 'Управление балансом пользователей';
$_lang['partnerprogram_balance_id'] = 'ID';
$_lang['partnerprogram_balance_user_id'] = 'Пользователь';
$_lang['partnerprogram_balance_possible_balance'] = 'Доступно к выплате';
$_lang['partnerprogram_balance_paid_balance'] = 'Выплачивается';
$_lang['partnerprogram_balance_paid'] = 'Выплачено';
$_lang['partnerprogram_balance_phone'] = 'Телефон';
$_lang['partnerprogram_balance_card'] = 'Карта';

/* --------------------- Вознаграждения -------------- */
$_lang['partnerprogram_rewards'] = 'Вознаграждения';
$_lang['partnerprogram_rewards_intro_msg'] = 'Управление вознаграждением';
$_lang['partnerprogram_rewards_id'] = 'ID';
$_lang['partnerprogram_rewards_user_id'] = 'Пользователь';
$_lang['partnerprogram_rewards_object_id'] = 'Объект';
$_lang['partnerprogram_rewards_sum'] = 'Сумма';
$_lang['partnerprogram_rewards_description'] = 'Описание';

/* --------------------- История -------------- */
$_lang['partnerprogram_history'] = 'История изменения';
$_lang['partnerprogram_history_intro_msg'] = 'Управление историей';
$_lang['partnerprogram_history_id'] = 'ID';
$_lang['partnerprogram_history_user_id'] = 'Пользователь';
$_lang['partnerprogram_history_object_id'] = 'Объект';
$_lang['partnerprogram_history_timestamp'] = 'Дата';
$_lang['partnerprogram_history_action'] = 'Действие';
$_lang['partnerprogram_history_description'] = 'Описание';
$_lang['partnerprogram_history_ip'] = 'IP';
$_lang['partnerprogram_history_entry'] = 'Доп. поле';