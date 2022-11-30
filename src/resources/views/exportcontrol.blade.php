<button uk-toggle="target: #export_{{$format}}" class="uk-button uk-button-default" type="button">Export {{$format}}</button>
<div id="export_{{$format}}" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <form action="/export" method="post">
            <h2 class="uk-modal-title">Export list to {{$format}}</h2>
            <p>Please choose <span class="uk-text-warning">at least one</span> column to include.</p>
            <div>
                <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                    <label><input class="uk-checkbox" name="export[columns][]" type="checkbox" value="book_title"> Title</label>
                    <label><input class="uk-checkbox" name="export[columns][]" type="checkbox" value="authors_name"> Author's Name</label>
                </div>
            </div>
            <hr>
            <div class="uk-text-right">
                <input type="submit" value="Export" class="uk-button uk-button-primary"/>
                <button class="uk-modal-close uk-button uk-button-danger" type="button">Cancel</button>
            </div>
            @csrf
            <input type="hidden" name="export[format]" value="{{$format}}">
            <input type="hidden" name="search" value="{{$search}}"/>
            <input type="hidden" name="list[order_by]" value="{{$list['order_by']}}"/>
            <input type="hidden" name="list[direction]" value="{{$list['direction']}}"/>
        </form>
    </div>
</div>
