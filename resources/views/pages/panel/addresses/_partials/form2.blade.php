<form action="" method="">
    <div class="row w-100 m-0">
        <div class="col-md-3 col-6 p-1">
            <label class="form-label font-small font-re mb-1" for="subject">عنوان آدرس</label>
            <input class="form-control" placeholder="مثلا خانه" id="subject" value="اداری">
        </div>
        <div class="col-md-3 col-6 p-1">
            <label class="form-label font-small font-re mb-1" for="postalCode">کدپستی</label>
            <input class="form-control" placeholder="1234567890" id="postalCode" value="1234567890">
        </div>
        <div class="col-md-3 col-6 p-1">
            <label class="form-label font-small font-re mb-1">استان</label>
            <select class="form-select" aria-label="Default select example">
                <option>استان محل سکونت</option>
                <option value="1">تهران</option>
                <option value="2">مشهد</option>
                <option selected value="3">آذربایجان شرقی</option>
            </select>
        </div>
        <div class="col-md-3 col-6 p-1">
            <label class="form-label font-small font-re mb-1">شهر</label>
            <select class="form-select" aria-label="Default select example">
                <option>شهر محل سکونت</option>
                <option value="1">تهران</option>
                <option value="2">مشهد</option>
                <option selected value="3">تبریز</option>
            </select>
        </div>
        <div class="col-12 p-1">
            <label class="form-label font-small font-re mb-1" for="exampleFormControlTextarea1">آدرس</label>
            <textarea class="form-control small" placeholder="خیابان اصلی,خیابان فرعی,کوچه" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
        <div class="d-flex justify-content-center mt-1">
            <button type="submit" class="btn btn-one btn-sm py-2 px-3">ثبت
                تغییرات</button>
        </div>
    </div>
</form>