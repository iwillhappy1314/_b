<?php get_header(); ?>

<a href="/account/" class="tk-go-back">
    <i class="rsicon-back"></i>
    <div>Bind Tiktok UID</div>
</a>

<div class="bg-white p-6 mb-6 mx-4 shadow-lg rounded-lg">
    <div class="text-center mb-4">
        <i class="rsicon-warning text-[86px] text-gray-500"></i>
    </div>
    When you use Sharesavvy for the first time, you need to bind your <span class="text-primary">TikTok UID</span>.
    UID is your unique identifier on TikTok and cannot be changed after binding.
</div>

<form id="tk-bind-form" action="">
    <div class="mx-4">
        <dib class="grid grid-cols-1 gap-6">
            <input type="text" name="tk-uid" value="<?= get_user_meta(get_current_user_id(), 'tk-uid', true); ?>" placeholder="Please enter your TikTok UID (19 digits)">
            <input type="text" name="tk-followers" value="<?= get_user_meta(get_current_user_id(), 'tk-followers', true); ?>" placeholder="Your TikTok Followers Number">
            <input type="text" name="tk-name" value="<?= get_user_meta(get_current_user_id(), 'tk-name', true); ?>" placeholder="Your TikTok Name">
        </dib>

        <div class="text-center mt-6">
            <button type="submit" class="rs-button--base px-8 bg-white border border-solid border-gray-300 py-2 rounded">OK</button>
        </div>
    </div>
</form>

<div class="bg-white rounded-lg p-4 mb-6 mx-4 mt-8 shadow">
    <h2>How to find your tiktok uid</h2>
</div>

<?php get_footer(); ?>