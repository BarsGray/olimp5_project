jQuery(document).ready(function($) {
    // Функция для обработки изменения выбора услуги
    function handleServiceSelect(serviceElement) {
        // Получаем ID услуги из скрытого поля
        const hiddenInput = serviceElement.find('input[name*="[_service_select]"]');
        
        if (!hiddenInput.length) {
            console.log('Hidden input not found');
            return;
        }
        
        const serviceValue = hiddenInput.val();
        console.log('Service value:', serviceValue);
        
        if (!serviceValue || serviceValue === '') {
            return;
        }
        
        // Извлекаем ID из значения формата "post:center-services:5313"
        const serviceId = serviceValue.split(':')[2];
        
        if (!serviceId) {
            console.log('Service ID not found in value:', serviceValue);
            return;
        }
        
        console.log('Extracted service ID:', serviceId);
        
        // Находим родительский контейнер комплексного поля
        const parentGroup = serviceElement.closest('.cf-complex__group');
        
        if (!parentGroup.length) {
            console.log('Parent group not found');
            return;
        }
        
        // Показываем индикатор загрузки
        const loadingIndicator = $('<div class="carbon-loading" style="padding: 10px; background: #f0f0f1; border: 1px solid #ddd; margin: 10px 0;">Загрузка данных услуги...</div>');
        parentGroup.find('.cf-complex__group-body').prepend(loadingIndicator);
        
        // AJAX запрос для получения данных услуги
        $.ajax({
            url: carbonFieldsAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_service_data',
                service_id: serviceId,
                nonce: carbonFieldsAjax.nonce
            },
            success: function(response) {
                console.log('AJAX response:', response);
                
                if (response.success) {
                    const data = response.data;
                    
                    // Находим поля в том же комплексном элементе
                    const nameField = parentGroup.find('input[name*="[_name]"]');
                    const priceField = parentGroup.find('input[name*="[_price]"]');
                    const doctorField = parentGroup.find('input[name*="[_doctor]"]');
                    
                    console.log('Found fields:', {
                        name: nameField.length,
                        price: priceField.length,
                        doctor: doctorField.length
                    });
                    
                    // Заполняем поля, если данные есть
                    if (nameField.length && data.name) {
                        nameField.val(data.name).trigger('change');
                        console.log('Name field updated:', data.name);
                    }
                    
                    if (priceField.length && data.price) {
                        priceField.val(data.price).trigger('change');
                        console.log('Price field updated:', data.price);
                    }
                    
                    if (doctorField.length && data.doctor) {
                        doctorField.val(data.doctor).trigger('change');
                        console.log('Doctor field updated:', data.doctor);
                    }
                    
                    // Показываем уведомление об успехе
                    showNotification('Данные услуги успешно загружены!', 'success');
                } else {
                    console.error('AJAX error:', response);
                    showNotification('Ошибка при загрузке данных услуги: ' + (response.data || 'Неизвестная ошибка'), 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', {xhr, status, error});
                showNotification('Ошибка при загрузке данных услуги: ' + error, 'error');
            },
            complete: function() {
                // Убираем индикатор загрузки
                loadingIndicator.remove();
            }
        });
    }
    
    // Функция для показа уведомлений
    function showNotification(message, type) {
        const notificationClass = type === 'success' ? 'notice-success' : 'notice-error';
        const notification = $(`
            <div class="notice ${notificationClass} is-dismissible" style="margin: 10px 0;">
                <p>${message}</p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Закрыть это уведомление.</span>
                </button>
            </div>
        `);
        
        // Добавляем уведомление в верхнюю часть страницы
        if ($('.wrap h1').length) {
            $('.wrap h1').after(notification);
        } else {
            $('body').prepend(notification);
        }
        
        // Автоматически скрываем через 5 секунд
        setTimeout(() => {
            notification.fadeOut();
        }, 5000);
        
        // Обработчик для кнопки закрытия
        notification.find('.notice-dismiss').on('click', function() {
            notification.fadeOut();
        });
    }
    
    // Функция для отслеживания изменений в association поле
    function bindAssociationEvents() {
        // Отслеживаем клики на элементы association
        $(document).off('click.carbonAssociation', '.cf-association__option').on('click.carbonAssociation', '.cf-association__option', function() {
            const $this = $(this);
            
            // Небольшая задержка, чтобы скрытое поле успело обновиться
            setTimeout(function() {
                const associationField = $this.closest('.cf-association');
                handleServiceSelect(associationField);
            }, 100);
        });
        
        // Отслеживаем изменения в скрытых полях association
        $(document).off('change.carbonAssociation', 'input[name*="[_service_select]"]').on('change.carbonAssociation', 'input[name*="[_service_select]"]', function() {
            const associationField = $(this).closest('.cf-association');
            handleServiceSelect(associationField);
        });
        
        // Отслеживаем удаление элементов из association
        $(document).off('click.carbonAssociationRemove', '.cf-association__option-action[aria-label="Remove"]').on('click.carbonAssociationRemove', '.cf-association__option-action[aria-label="Remove"]', function() {
            const $this = $(this);
            
            setTimeout(function() {
                const associationField = $this.closest('.cf-association');
                const hiddenInputs = associationField.find('input[name*="[_service_select]"]');
                
                // Если нет выбранных элементов, очищаем поля
                if (hiddenInputs.length === 0 || hiddenInputs.val() === '') {
                    const parentGroup = associationField.closest('.cf-complex__group');
                    parentGroup.find('input[name*="[_name]"]').val('');
                    parentGroup.find('input[name*="[_price]"]').val('');
                    parentGroup.find('input[name*="[_doctor]"]').val('');
                }
            }, 100);
        });
    }
    
    // Функция для обработки добавления новых комплексных групп
    function bindComplexGroupEvents() {
        // Отслеживаем добавление новых комплексных групп
        $(document).off('click.carbonComplex', '.cf-complex__inserter-button').on('click.carbonComplex', '.cf-complex__inserter-button', function() {
            setTimeout(function() {
                bindAssociationEvents();
                console.log('New complex group added, events rebound');
            }, 500);
        });
        
        // Отслеживаем дублирование групп
        $(document).off('click.carbonDuplicate', '.cf-complex__group-action[title="Duplicate"]').on('click.carbonDuplicate', '.cf-complex__group-action[title="Duplicate"]', function() {
            setTimeout(function() {
                bindAssociationEvents();
                console.log('Complex group duplicated, events rebound');
            }, 500);
        });
    }
    
    // Инициализация
    function init() {
        console.log('Carbon Fields Auto Fill initialized');
        bindAssociationEvents();
        bindComplexGroupEvents();
        
        // Проверяем существующие выбранные услуги при загрузке страницы
        $('.cf-association input[name*="[_service_select]"]').each(function() {
            if ($(this).val()) {
                const associationField = $(this).closest('.cf-association');
                handleServiceSelect(associationField);
            }
        });
    }
    
    // Запускаем инициализацию после полной загрузки DOM
    init();
    
    // Дополнительная инициализация для случаев динамической загрузки
    $(window).on('load', function() {
        setTimeout(init, 1000);
    });
});