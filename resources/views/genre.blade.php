{{ 'genre page' }}

               
<form method="post" action="{{ route('saveGenre') }}" accept-charset="UTF-8">
                    {{ csrf_field() }}
                
                    <label for="genre">New genre</label></br>
                    <input type="text" name="genre"></input></br>
                    <button name="addGenre">save</button>

</form>