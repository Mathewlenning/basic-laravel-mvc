
<form action="/" method="post">
<a href="javascript:void(0);" onclick="jQuery(this).closest('form').submit()">
    {{$label}}
        @if ($list['order_by'] == $sortField)
            @if (strtoupper($list['direction']) == 'ASC')
            <span uk-icon="icon:chevron-up"></span>
            @else
            <span uk-icon="icon:chevron-down"></span>
            @endif
        @endif
    </a>
    @csrf
    <input type="hidden" name="list[order_by]" value="{{$sortField}}"/>
    <input type="hidden" name="search" value="{{$search}}"/>

    @if ($list['order_by'] == $sortField)
        @if (strtoupper($list['direction']) == 'ASC')
            <input type="hidden" name="list[direction]" value="DESC"/>
        @else
            <input type="hidden" name="list[direction]" value="ASC"/>
        @endif
    @else
        <input type="hidden" name="list[direction]" value="ASC"/>
    @endif
</form>
