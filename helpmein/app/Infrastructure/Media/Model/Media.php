<?php

declare(strict_types=1);

namespace App\Infrastructure\Media\Model;

use App\Infrastructure\Model\Traits\UsesUuidTrait;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Расширяет класс модели библиотеки, убирает ненужную нам функциональность
 *
 * @property string id - UUID
 * @property string model_id - UUID модели
 * @property string model_type - FQCN модели
 * @property string collection_name - Название коллекции
 * @property string file_name - Название файла
 * @property string disk - Название диска
 * @property array custom_properties - Json с доп. данными
 */
class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media
{
    use UsesUuidTrait;

    // Документы, подтверждающие работу специалиста в агентстве
    public const COLLECTION_SPECIALIST_WORK_APPROVE = 'specialist-work-approve';

    // Фотография специалиста
    public const COLLECTION_SPECIALIST_PHOTO = 'specialist-photo';

    // Временные файлы
    public const COLLECTION_TMP_FILES = 'tmp-files';

    // Логотип агентства
    public const COLLECTION_AGENCY_LOGO = 'agency-logo';

    // Логотип клиента
    public const COLLECTION_CLIENT_LOGO = 'client-logo';

    // Шаблоны документов
    public const COLLECTION_DOCUMENT_TEMPLATES = 'document-template';

    // Контракты
    public const COLLECTION_AGENCY_DOCUMENTS = 'agency-documents';

    // Аватар пользователя
    public const COLLECTION_USER_AVATAR = 'user-avatar';

    // Сертификаты специалиста
    public const COLLECTION_SPECIALIST_CERTIFICATES = 'specialist-certificates';

    // Анкеты специалиста
    public const COLLECTION_SPECIALIST_QUESTIONNAIRES = 'specialist-questionnaires';

    // Тесты специалиста
    public const COLLECTION_SPECIALIST_TESTS = 'specialist-tests';

    // Дополнительные затрты для актов
    public const COLLECTION_ADDITIONAL_EXPENSES = 'additional-expenses';

    // Описание к запросу на подбор специалистов
    public const COLLECTION_SELECTION_REQUEST_DESCRIPTION = 'selection-request-description';

    // Требования к роли из запроса на подбор специалистов
    public const COLLECTION_SELECTION_REQUEST_ROLE_REQUIREMENTS = 'selection-request-role-requirements';

    // Документ клиента
    public const COLLECTION_CLIENT_DOCUMENTS = 'client-documents';

    // Счет по отчету клиента
    public const COLLECTION_CLIENT_BILL = 'client-bill';

    // Счёт-фактура для счёта по отчёту клиента
    public const COLLECTION_CLIENT_BILL_INVOICE = 'client-bill-invoice';

    // Тестовые задания для специалиста
    public const COLLECTION_TEST_ASSIGNMENT = 'specialist-test-assignment';

    // Отчет клиента
    public const COLLECTION_CLIENT_REPORT = 'client-report';

    // Акт к отчету клиента с договором Договор T&M (старый)
    public const COLLECTION_CLIENT_REPORT_ACT_TM_OLD = 'client-report-act-tm-old';

    // Счет-фактура к отчету клиента
    public const COLLECTION_CLIENT_REPORT_INVOICE = 'client-report-invoice';

    // Акт выполненных работ
    public const COLLECTION_ACT = 'act';

    // Акт выполненных работ для агентств с договором типа Подряд АВГ старый
    public const COLLECTION_ACT_AWG_OLD = 'act-awg-old';

    // Счет по акту выполненных работ для агентств с договором типа Подряд АВГ старый
    public const COLLECTION_ACT_BILL_AWG_OLD = 'act-bill-awg-old';

    // Счет по акту выполненных работ для агентств с договором типа Подряд АВГ новый
    public const COLLECTION_ACT_BILL_AWG_NEW = 'act-bill-awg-new';

    // Жалобы на специалистов
    public const COLLECTION_CLAIM_FILES = 'claims';

    // Дополнительные требования на проекте
    public const COLLECTION_SPECIALIST_REQUEST_PROJECT_DESCRIPTION = 'specialist-request-project-description';

    // Обратная связь
    public const COLLECTION_FEEDBACK = 'feedback';

    // Бумажная заявка для Клиента
    public const COLLECTION_CLIENT_SPECIALIST_REQUEST = 'collection-client-specialist-request';

    // Бумажная заявка для Агентства
    public const COLLECTION_AGENCY_SPECIALIST_REQUEST = 'collection-agency-specialist-request';

    // Уведомление о расторжении для Клиента (работающий специалист)
    public const COLLECTION_CLIENT_TERMINATION_NOTICE = 'collection-client-termination-notice';

    // Уведомление о расторжении для Агентства (работающий специалист)
    public const COLLECTION_AGENCY_TERMINATION_NOTICE = 'collection-agency-termination-notice';

    // Файл, прикрепленный к комментарию
    public const COLLECTION_COMMENT_FILE = 'collection-comment-file';

    // Превью статьи блога
    public const COLLECTION_BLOG_ARTICLE_PREVIEW = 'collection-blog-article-preview';
    public const COLLECTION_BLOG_ARTICLE_PREVIEW_MEDIUM = 'collection-blog-article-preview-medium';
    public const COLLECTION_BLOG_ARTICLE_PREVIEW_SMALL = 'collection-blog-article-preview-small';

    // Файловое хранилище (свалка разных нужных файлов)
    public const COLLECTION_FILE_STORAGE = 'collection-file-storage';

    // Картинки для статьи блога
    public const COLLECTION_BLOG_ARTICLE = 'collection-blog-article';

    // Печатная форма подзаявки(кандидата) для Клиента
    public const COLLECTION_CLIENT_SINGLE_SPECIALIST_REQUEST = 'collection-client-single-specialist-request';

    // Печатная форма подзаявки(кандидата) для Агентства
    public const COLLECTION_AGENCY_SINGLE_SPECIALIST_REQUEST = 'collection-agency-single-specialist-request';

    // Тестовое задание для вакансии из Сейчас ищут
    public const COLLECTION_ACTUAL_VACANCY_TEST_TASK = 'collection-actual-vacancy-test-task';

    /**
     * Переопределим родительский метод, ничего делать не нужно
     */
    public static function bootHasUuid(): void
    {
    }

    /**
     * Не нужно добавлять инкрементную сортировку при вставке
     * @return bool
     */
    public function shouldSortWhenCreating(): bool
    {
        return false;
    }

    /**
     * Переопределяем этот метод, чтобы добавить возможность передачи имени файла через Request,
     * и правильно сформировать заголовок Content-Disposition
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function toResponse($request)
    {
        $fileName = $request->input('file_name', $this->file_name);

        $filenameFallback = str_replace('%', '', Str::ascii($fileName));

        $downloadHeaders = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type' => $this->mime_type,
            'Content-Length' => $this->size,
            'Content-Disposition' => HeaderUtils::makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName, $filenameFallback),
            'Pragma' => 'public',
        ];

        return response()->stream(function () {
            $stream = $this->stream();

            fpassthru($stream);

            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, $downloadHeaders);
    }
}
