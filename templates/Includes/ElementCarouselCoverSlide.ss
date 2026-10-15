
<%-- <div class="element-cover-slide swiper-slide" style="background-image: url('$Image.URL');">
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
</div> --%>

<div class="swiper-slide">
<div class="card cover-card">
    <% if $Image %>
        <picture>
            <source media="(min-width:1024px)" srcset="<% if function_exists('FocusFill') %>$Image.FocusFill(600,600).URL<% else %>$Image.Fill(600,600).URL<% end_if %>">
            <source media="(max-width:1023px)" srcset="<% if function_exists('FocusFill') %>$Image.FocusFill(600,600).URL<% else %>$Image.Fill(600,600).URL<% end_if %>">
            <img src="<% if function_exists('FocusFill') %>$Image.FocusFill(600,600).URL<% else %>$Image.ScaleMaxWidth(600).URL<% end_if %>" alt="$Image.Title.ATT" width="600" height="600" style="width:100%;height:auto;aspect-ratio:<% if $CardRatio %>$CardRatio<% else %>3/4<% end_if %>;object-fit:cover;">
        </picture>
    <% end_if %>
    <%-- A div, not a link like the promo cover card: slides can have several button links --%>
    <div class="flex-container card-section <% if $CoverVAlignClass %>$CoverVAlignClass<% else %>align-bottom<% end_if %><% if $CardAlignClass %> $CardAlignClass<% end_if %>">
        <div class="$CoverPaddingClass">
        <% if $Title && not $HideTitle %><h3 class="h2" style="margin-bottom:0.25rem">$Title</h3><% end_if %>
        $Content
        <% if $Links.Exists %>
            <div class="button-group small <% if $CardHAlign == 'center' %>align-center<% else_if $CardHAlign == 'right' %>align-right<% else %>align-left<% end_if %>">
            <% loop $Links %>
                <a class="button small $CssClass" href="$URL" <% if $RemodalTarget %>data-remodal-target="$RemodalTarget"<% end_if %> <% if $OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>>$Title.XML</a>
            <% end_loop %>
            </div>
        <% end_if %>
        </div>
    </div>
</div>
</div>
