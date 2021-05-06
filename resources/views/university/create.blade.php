<form method="POST" enctype="multipart/form-data">
    @csrf
    Short name<br>
    <input name="university_short_name" required><br>
    Full name<br>
    <input name="university_full_name" required><br>
    Description<br>
    <textarea name="university_description"></textarea><br>
    Image<br>
    <input type="file" name="university_image" accept="image/*" required><br>
    Public
    <input type="checkbox" name="university_public" checked><br>
    <input type="submit">
</form>
