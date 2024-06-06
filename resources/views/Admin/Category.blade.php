@extends('Components.Admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('CSS/Admin/category.css') }}">
@endpush

@section('main')
    <main id="category">
        <div class="path">
            <p>Dashboard > Categories</p>
        </div>

        <div class="newCategory">
            <button onclick="addCategory()">Add New Category</button>
        </div>

        @php
            $categories = $categories;
        @endphp

        <table id="categoryTable">
            <thead>
                <tr>
                    <th>Category Image</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $value)
                    <tr>
                        <td><img src={{ asset('Images/CategoryImages/' . $value->categoryImage) }} alt="Image"></td>
                        <td>{{ $value->categoryName }}</td>
                        <td>
                            <a onclick="editcategory('{{ $value->categoryName }}', '{{ $value->id }}')"><i
                                    class='bx bxs-edit-alt'></i></a>
                            <a href="{{ route('deleteCategory', $value->id) }}"><i class='bx bxs-trash-alt'></i></a>
                        </td>
                    </tr>
                    @php
                        $name = $value->categoryName;
                        $id = $value->id;
                    @endphp
                @endforeach
            </tbody>
        </table>
        {{-- <div class="pagination"> {{ $categories->links() }} </div> --}}

        {{--  
            |---------------------------------------------------------------|
            |================ Add new Category Overlay =====================|
            |---------------------------------------------------------------|
        --}}

        <div id="overlay"></div>
        <form class="newcategory" id="newCategory" action="{{ route('createCategory') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3 id="345ggbh">Add New Category</h3>
            <hr>

            <div class="inputdivs">
                <label for="upload-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="file" id="upload-file" name="CategoryImage" accept=".jpg,.jpeg,.png" required>
                    <p id="filename"></p>
                </label>
            </div>
            @error('CategoryImage')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <div class="inputdivs">
                <label for="categoryname">Category Name</label>
                <input type="text" id="categoryname" name="categoryName" placeholder="Category Name" required>
            </div>
            @error('categoryName')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <div class="btns">
                <button id="cancel" onclick="closeAddCatogry()">Cancel</button>
                <input type="submit" value="Add">
            </div>
        </form>


        {{--   
            |---------------------------------------------------------------|
            |================== Edit Category Overlay ======================|
            |---------------------------------------------------------------|
        --}}

        <div id="editOverlay"></div>
        <form class="updateCategory" id="updateCategory" action="{{ route('updateCategory') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <h3>Update Category</h3>
            <hr>

            <div class="inputdivs">
                <label for="upload-update-file" class="choose-file-btn">
                    <span>Choose File</span>
                    <input type="hidden" id="upid" name="id">
                    <input type="file" id="upload-update-file" name="CategoryImage" accept=".jpg,.jpeg,.png">
                    <p id="filenam"></p>
                </label>
            </div>
            @error('CategoryImage')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <div class="inputdivs">
                <label for="categorynam">Category Name</label>
                <input type="text" id="categorynam" name="categoryName"placeholder="Category Name" required>
            </div>
            @error('categoryName')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <div class="btns">
                <button id="cancel" onclick="closeEditCatogry()">Cancel</button>
                <input type="submit" value="Update">
            </div>
        </form>

    </main>

    <script>
        const Data = @json($categories);
        const categoryNames = Data.map(category => category.categoryName);
        const SEARCHBAR = document.getElementById('search');

        function searchCategory() {
            let filter, table, tr, td, i, txtValue;
            filter = SEARCHBAR.value.toUpperCase();
            table = document.getElementById("categoryTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        SEARCHBAR.addEventListener('keyup', searchCategory);

        function addCategory() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newCategory');
            overlay.style.display = 'block';
            popup.style.display = 'flex';
        }

        function closeAddCatogry() {
            let overlay = document.getElementById('overlay');
            let popup = document.getElementById('newCategory');
            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        const uploadFile = document.getElementById('upload-file');
        const filenameSpan = document.getElementById('filename');
        uploadFile.addEventListener('change', function(e) {
            const fileName = this.value.split('\\').pop();
            filenameSpan.textContent = fileName ? fileName : 'No file chosen';
        });

        function editcategory(categoryName, id) {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('updateCategory');
            overlay.style.display = 'block';
            popup.style.display = 'flex';
            document.getElementById('categorynam').value = categoryName;
            document.getElementById('upid').value = id;
        }

        function closeEditCatogry() {
            let overlay = document.getElementById('editOverlay');
            let popup = document.getElementById('updateCategory');
            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        const uploadUpdatedFile = document.getElementById('upload-update-file');
        const filenamSpan = document.getElementById('filenam');
        uploadUpdatedFile.addEventListener('change', function(e) {
            const fileNam = this.value.split('\\').pop();
            filenamSpan.textContent = fileNam ? fileNam : 'No file chosen';
        });
    </script>
@endsection
