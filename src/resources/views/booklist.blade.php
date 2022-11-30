<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Using UIKit because this is the front-end framework I'm most familiar with -->
        <!-- UIkit CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.11.1/dist/css/uikit.min.css" />

        <style>
            .invalidForm
            {
                border-color: #f0506e!important;
            }
        </style>
        <!-- UIkit JS -->
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.11.1/dist/js/uikit.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.11.1/dist/js/uikit-icons.min.js"></script>
        <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>

        <script type="text/javascript">

            jQuery(document).ready(function()
            {
                jQuery('#addForm [data-required]').on('change', function (){
                    jQuery(this).removeClass('invalidForm');
                })
            });

            function editBook(event)
            {
                var targ = jQuery(event.target || event.srcElement);
                var parentTr = targ.closest('tr');
                var form = jQuery('#addForm');

                form.find('input[type!="submit"][name!="_token"]').each(function (index, element)
                {
                   element = jQuery(element);

                   var dataValueName = 'data-'+element.attr('data-plain_name');
                   var dataValue = parentTr.attr(dataValueName);
                   element.val(dataValue);
                });

                switchAltText(form);
            }

            function switchAltText(form)
            {
                var altTextElements = form.find('[data-alt_text]');

                altTextElements.each(function (index, element)
                {
                    var tagName = element.tagName
                    element = jQuery(element);
                    var currentText =  element.text();

                    if(tagName.toUpperCase() === 'INPUT')
                    {
                        currentText =  element.val();
                        element.val(element.attr('data-alt_text'));
                        element.attr('data-alt_text', currentText);
                        return;
                    }

                    element.text(element.attr('data-alt_text'));
                    element.attr('data-alt_text', currentText);
                });
            }

            function resetEditForm()
            {
                var form = jQuery('#addForm');

                //reset the input values
                form.find('input[type!="submit"][name!="_token"]').each(function (index, element)
                {
                    element = jQuery(element);
                    element.val("");
                });

                if(form.find('legend').text().toUpperCase() === 'EDIT A BOOK') {
                    switchAltText(form);
                }
            }

            function validateForm(event)
            {
                var form = jQuery('#addForm');
                var valid = true;

                form.find('[data-required]').each(function (index, element){
                    element = jQuery(element);
                    element.removeClass('invalidForm');

                    if(element.val() === '')
                    {
                        element.addClass('invalidForm');
                        valid = false;
                    }
                });

                if (valid === false)
                {
                    event.preventDefault();
                    return false;
                }

                return valid;
            }
        </script>
        <title>Welcome to BookFace</title>

    </head>
    <body>
    <div class="uk-container">
        <div class="uk-section uk-section-primary uk-padding-remove-top uk-padding-remove-bottom">
            <div class="uk-container-expand uk-child-width-1-1@s uk-child-width-1-2@m uk-padding-small" uk-grid>
                <div class="uk-flex uk-flex-middle">
                    <h3 class="uk-heading-small uk-margin-left uk-margin-remove-bottom">BookFace <br/>
                        <span class="uk-text-large uk-text-top uk-text-bold uk-text-muted">What's on your list</span></h3>
                </div>
                <div>
                    <form id="addForm" class="uk-form-stacked" action="/" method="post" onsubmit="validateForm(event)">
                        <fieldset class="uk-fieldset">
                            <legend class="uk-legend" data-alt_text="Edit a book">Add a book</legend>
                            <div class="uk-margin">
                                <label for="addform[data][book_title]" class="uk-form-label">Title <span class="uk-text-danger">*</span></label>
                                <input name="addform[data][book_title]" data-required="true" data-plain_name="book_title" class="uk-input" type="text"/>
                            </div>
                            <div class="uk-grid-small uk-child-width-1-1@s uk-child-width-1-2@m" uk-grid>
                                <div>
                                    <label for="addform[data][author_first]" class="uk-form-label">Author's First Name <span class="uk-text-danger">*</span></label>
                                    <input name="addform[data][author_first_name]" data-required="true" data-plain_name="author_first_name" class="uk-input" type="text">
                                </div>
                                <div>
                                    <label for="addform[data][author_last]" class="uk-form-label">Author's Last Name <span class="uk-text-danger">*</span></label>
                                    <input name="addform[data][author_last_name]" data-required="true" data-plain_name="author_last_name" class="uk-input" type="text">
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-form-controls">
                                    <hr>
                                    <input type="submit" value="Add" class="uk-button uk-button-secondary uk-button-small" data-alt_text="Update"/>
                                    <a href="javascript:void(0);" class="uk-button uk-button-danger uk-button-small" onclick="resetEditForm();">Reset</a>
                                </div>
                            </div>
                        </fieldset>
                        @csrf
                        <input type="hidden" name="addform[data][book_id]" data-plain_name="book_id" value=""/>
                    </form>
                </div>
            </div>
        </div>
        @if (!empty($message))
            <div class="uk-alert-{{$message_type}}" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p>{{$message}}</p>
            </div>
        @endif
        <div class="uk-section uk-padding-remove-top uk-padding-remove-bottom">
            <div class="uk-container-expand uk-margin-top uk-child-width-1-1@s uk-child-width-1-2@m" uk-grid>
                <div>
                    <div>
                        @include('exportcontrol', ['format' => 'CSV'])
                        @include('exportcontrol', ['format' => 'XML'])
                    </div>
                </div>
                <div>
                    <form id="searchForm" action="/" method="post">
                        <div class="uk-flex uk-flex-right uk-grid-small" uk-grid>
                            <div>
                            <div class="uk-inline">
                                <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
                                <input class="uk-input" id="searchInput" type="text" uk-tooltip="Search by book title <br/>or author's name" name="search" placeholder="Search" value="{{$search}}"/>
                            </div>
                            </div>
                            <div>
                                <input type="submit" value="Search" class="uk-button uk-button-primary">
                                <a href="javascript:void(0);" onclick="jQuery('#searchInput').val(''); jQuery(this).closest('form').submit()" class="uk-button uk-button-danger">Clear</a>
                            </div>
                        </div>
                        @csrf
                        <input type="hidden" name="list[order_by]" value="book_title"/>
                        <input type="hidden" name="list[direction]" value="ASC"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="uk-section uk-padding-remove-top">
            <table class="uk-table uk-table-responsive uk-table-divider">
                <thead>
                <tr>
                    <th class="uk-width-1-2@m">
                        @include('sortcontrol', ['sortField' => 'book_title', 'label' => 'Title'])
                    </th>
                    <th>
                        @include('sortcontrol', ['sortField' => 'author_last_name', 'label' => 'Author'])
                    </th>
                    <td></td>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="3"></td>
                </tr>
                </tfoot>
                <tbody>
                @foreach($books AS $book)
                <tr data-book_id="{{$book->book_id}}"
                    data-book_title="{{$book->book_title}}"
                    data-author_first_name="{{$book->author_first_name}}"
                    data-author_last_name="{{$book->author_last_name}}">
                    <td>{{$book->book_title}}</td>
                    <td >{{$book->author_first_name}} {{$book->author_last_name}}</td>
                    <td class="uk-text-right">
                        <button class="uk-button uk-button-default uk-button-small" uk-icon="icon:pencil" onclick="editBook(event)"></button>
                        <form action="/" method="post" style="display: inline">
                        <button class="uk-button uk-button-danger uk-button-small" uk-icon="icon:trash"></button>
                            @csrf
                            @method('delete')
                            <input type="hidden" name="book_id" value="{{$book->book_id}}"/>
                            <input type="hidden" name="search" value="{{$search}}"/>
                            <input type="hidden" name="list[order_by]" value="{{$list['order_by']}}"/>
                            <input type="hidden" name="list[direction]" value="{{$list['direction']}}"/>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if ($books->isEmpty())
                    <tr>
                        <td colspan="3" class="uk-text-center">
                            <h1>No Records Found</h1>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    </body>
</html>
