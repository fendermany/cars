<form
    class="search-form"
    role="search"
    method="get"
    id="searchform"
    action="<?php echo home_url('/') ?>"
>
    <input
        class="search-form__input"
        type="text"
        value="<?php echo get_search_query() ?>"
        name="s" id="s"
        placeholder="Поиск на сайте"
        autocomplete="off"
    />
    <button type="submit" id="searchsubmit">
        <svg xmlns="http://www.w3.org/2000/svg" width="19.856" height="20.848" viewBox="0 0 19.856 20.848">
            <path d="M91.119,310.567l-4.713-4.713a8.8,8.8,0,0,0,2.51-6.147,8.708,8.708,0,1,0-8.708,8.708,8.983,8.983,0,0,0,5.02-1.588l4.815,4.815a.877.877,0,0,0,1.127,0A.792.792,0,0,0,91.119,310.567ZM73.037,299.708a7.171,7.171,0,1,1,7.171,7.171A7.192,7.192,0,0,1,73.037,299.708Z" transform="translate(-71.5 -291)" fill="#414544" />
        </svg>
    </button>
    <ul class="ajax-search"></ul>
</form>