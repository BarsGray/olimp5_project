<?php 

get_header();

/*
Template Name: Налоговый вычет
*/

?>


        <div class="rb-vychet rb-page">
            <div class="rb-container">
                <div class="rb-page-header">
                    <h1><?php the_title(); ?></h1>
                </div>
                <section class="rb-vychet__infoblock">
                    <form method="POST" class="rb-contacts__form" id="rb-vychet-form">

                        <?php if( $_GET['success'] <> '') : ?>
                            
                            <script>window.location.href = '/success/';</script>

                        <?php endif; ?>

                        <div class="rb-form__error"></div>
                        <div class="rb-contacts__form-inputs flex-wrap">
                            <input type="hidden" name="action" value="rb_vychet">
                            <input name="rb_vychet_nonce" value="<?php echo wp_create_nonce( "rbVychetNonce" ); ?>" type="hidden">
                            <div class="col-12">
                                <label for="forwhom">Для кого оформляется форма?</label>
                                <div class="rb-vychet__forwhom">
                                    <span class="rb-vychet__forwhom-tab rb-vychet__active" data-tab="one">Для себя</span>
                                    <span class="rb-vychet__forwhom-tab" data-tab="two">Для другого человека</span>
                                </div>
								<input type="hidden" name="forwhom" id="rb-vychet-forwhom" value="one">

                            </div>
                            <div class="rb-vychet__container col-12">
                                
                                <?php get_template_part( 'template-part/forms/form', 'vychet-one' ); ?>
 
                            </div>
                           <div style="display:contents;" class="rb-vychet__form--button">
                                <div class="col-9 col-s-12">
                                    <p>Готовую справку Вы можете получить по адресу г.Воронеж, ул. Моисеева, 2/2. <br>
                                        Важно! При себе необходимо иметь паспорт. <br>
                                        Срок изготовления справки от 10 до 30 календарных дней. <br>
                                        Узнать статус готовности можно по телефону: <a href="tel:84732111568">+7 (473)211-15-68</a>
                                    </p>
                                </div>
                                <div class="col-3 col-s-12">

                                    <button class="rb-vychet__button" type="submit" >
                                        Отправить форму
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section> 
               
            </div>
        </div>
<script>
	document.addEventListener('click', function (e) {
  const t = e.target.closest('.rb-vychet__forwhom-tab');
  if (!t) return;

  const v = t.getAttribute('data-tab') === 'two' ? 'two' : 'one';
  const hidden = document.getElementById('rb-vychet-forwhom');
  if (hidden) hidden.value = v;
});

(function () {
  function qs(root, sel) { return (root || document).querySelector(sel); }
  function qsa(root, sel) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  function getYears(str) {
    if (!str) return [];
    const m = String(str).match(/\b(19|20)\d{2}\b/g);
    return m ? m.map(Number) : [];
  }
function findPaperBlock(wrap) {
  return qs(wrap, '[data-field="paper"]');
}
	function findLkBlock(wrap) {
  return qs(wrap, '[data-field="lk"]');
}
  function detectYearMode(periodStr) {
    const years = getYears(periodStr);
    if (!years.length) return null;

    const has2023 = years.includes(2023);
    const has2024 = years.includes(2024);
    const has2025 = years.includes(2025);

    if (has2023 && !has2024 && !has2025) return '2023';
    if (has2024 || has2025) return '2024_2025';
    return null;
  }

  function hideBlock(block, hidden) {
    if (!block) return;
    block.style.display = hidden ? 'none' : '';
    block.classList.toggle('rb-vychet__form-item-hide', hidden);

    qsa(block, 'input, select, textarea').forEach(function (el) {
      el.disabled = !!hidden;

      if (hidden && el.hasAttribute('required')) {
        el.dataset.wasRequired = '1';
        el.removeAttribute('required');
      }
      if (!hidden && el.dataset.wasRequired === '1') {
        el.setAttribute('required', '');
        delete el.dataset.wasRequired;
      }
    });
  }

  function setChooseActiveByClickedTab(choose, clickedTab) {
    if (!choose || !clickedTab) return;
    qsa(choose, '.rb-vychet__choose-tab').forEach(function (t) {
      t.classList.remove('rb-vychet__active');
    });
    clickedTab.classList.add('rb-vychet__active');
  }

function setHiddenBeforeChoose(choose, valueText) {
  if (!choose) return;

  const name = choose.dataset.name;
  if (!name) return;

  const hidden = choose
    .closest('form')
    .querySelector('input[type="hidden"][name="' + name + '"]');

  if (hidden) hidden.value = valueText;
}


  function toggleAddressBlocks(wrap, show) {
    qsa(wrap, '[data-where="address"]').forEach(function (el) {
      if (el.classList.contains('rb-vychet__choose')) return;
      el.classList.toggle('rb-vychet__form-item-hide', !show);
    });
  }

  function findPostChoose(wrap) {
    return qs(wrap, '.rb-vychet__choose[data-where="address"]');
  }

function findCopyBlocks(wrap) {
  return qsa(wrap, '[data-field="copy"]');
}

  function findAddressBlock(wrap) {
    return qs(wrap, '[data-field="post-address"]');
  }

function applyRulesForWrap(wrap) {
  if (!wrap) return;

  const periodInput = qs(wrap, 'input[name="rb_vychet_period"], input[name="rb_vychet_period2"]');
  const periodVal = periodInput ? periodInput.value : '';
  const mode = detectYearMode(periodVal);
  const years = getYears(periodVal);

  const copyBlocks = findCopyBlocks(wrap);
  const addressBlock = findAddressBlock(wrap);
  const postChoose = findPostChoose(wrap);
  const paperBlock = findPaperBlock(wrap);
  const lkBlock = findLkBlock(wrap);

  const showPaper = years.some(y => y >= 2023 && y <= 2025);
  hideBlock(paperBlock, !showPaper);

  const showLk = years.includes(2024) || years.includes(2025);
  hideBlock(lkBlock, !showLk);

  const hideCopy = years.includes(2024) || years.includes(2025);
  copyBlocks.forEach(function (b) { hideBlock(b, hideCopy); });

  if (mode === '2023' || mode === '2024_2025') {
    hideBlock(addressBlock, true);

if (postChoose) {
  if (postChoose.dataset.userTouched !== '1') {
    const yesTab = qs(
      postChoose,
      '.rb-vychet__choose-tab[data-tab="yes"], .rb-vychet__choose-tab[data-value="Да"]'
    );
    if (yesTab) setChooseActiveByClickedTab(postChoose, yesTab);
    setHiddenBeforeChoose(postChoose, 'Да');
  }

  toggleAddressBlocks(wrap, false);
}

  } else {
    hideBlock(addressBlock, false);
  }
}



  function applyRulesAll() {
    qsa(document, '.rb-vychet__wrap[data-tab="one"], .rb-vychet__wrap[data-tab="two"]').forEach(applyRulesForWrap);
  }

  document.addEventListener('click', function (e) {
    const tab = e.target.closest('.rb-vychet__choose-tab');
    if (!tab) return;

    const choose = tab.closest('.rb-vychet__choose');
    if (!choose) return;

    const wrap = tab.closest('.rb-vychet__wrap') || document;

    setChooseActiveByClickedTab(choose, tab);

    const val =
      tab.getAttribute('data-value') ||
      (tab.getAttribute('data-tab') === 'yes' ? 'Да' : (tab.getAttribute('data-tab') === 'no' ? 'Нет' : tab.textContent.trim()));

    const valueText = (val === 'Да' || val === 'Нет') ? val : (tab.textContent.trim() === 'Да' ? 'Да' : 'Нет');
    setHiddenBeforeChoose(choose, valueText);

if (choose.getAttribute('data-where') === 'address') {
  choose.dataset.userTouched = '1';
  toggleAddressBlocks(wrap, valueText === 'Да');
}

    const realWrap = tab.closest('.rb-vychet__wrap');
    if (realWrap) applyRulesForWrap(realWrap);
  });

  document.addEventListener('input', function (e) {
    if (!e.target) return;
    if (e.target.name === 'rb_vychet_period' || e.target.name === 'rb_vychet_period2') {
      const wrap = e.target.closest('.rb-vychet__wrap');
      applyRulesForWrap(wrap);
    }
  });

  document.addEventListener('click', function (e) {
    const t = e.target.closest('.rb-vychet__forwhom-tab');
    if (!t) return;
    setTimeout(applyRulesAll, 0);
  });

  document.addEventListener('DOMContentLoaded', applyRulesAll);
})();
</script>



<style>
/* Подсветка ошибки выбора */
.rb-vychet__choose--error {
  outline: 2px solid #d33;
  border-radius: 10px;
  padding: 4px;
}
</style>

<?php get_footer(); ?>