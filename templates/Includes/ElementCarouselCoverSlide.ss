
<div class="element-cover-slide swiper-slide" style="background-image: url('$Image.URL');">
    <div class="card-section dark-overlay p-40 $CssClass">
          <% if not $HideTitle %><h3 class="card-title">$Title</h3><% end_if %>
          $Content
          <% if $Links.Exists %>
            <div class="button-group small <% if $Align == 'center' %>align-center<% else_if $Align == 'right' %>align-right<% else %>align-left<% end_if %>">
              <% loop $Links %>
                <a class="button small $CssClass" href="$URL" <% if $RemodalTarget %>data-remodal-target="$RemodalTarget"<% end_if %> <% if $OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>>$Title.XML</a>
              <% end_loop %>
            </div>
          <% end_if %>
      </div>
</div>
