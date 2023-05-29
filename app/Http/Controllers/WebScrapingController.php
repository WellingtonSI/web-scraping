<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Weidner\Goutte\GoutteFacade;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Illuminate\Support\Facades\Http;



class WebScrapingController extends Controller
{

    public function webScraping()
    {

        $query = [
            "operationName" => "PropertyOffersQuery",
            "query" => 'query PropertyOffersQuery($context: ContextInput!, $propertyId: String!, $searchCriteria: PropertySearchCriteriaInput, $shoppingContext: ShoppingContextInput, $travelAdTrackingInfo: PropertyTravelAdTrackingInfoInput, $searchOffer: SearchOfferInput, $referrer: String) {
                propertyOffers(
                  context: $context
                  propertyId: $propertyId
                  referrer: $referrer
                  searchCriteria: $searchCriteria
                  searchOffer: $searchOffer
                  shoppingContext: $shoppingContext
                  travelAdTrackingInfo: $travelAdTrackingInfo
                ) {
                    id
                    ...PropertyLevelOffersMessageFragment
                    ...PropertyLevelOffersCardFragment
                    ...SingleOfferFragment
                    ...PropertyStickyBookBarFragment
                    ...PropertyStickyPriceHeaderFragment
                    ...AlternateDatesFragment
                    listings {
                    ...PropertyUnitFragment
                    }
                    categorizedListings {
                        ...PropertyUnitCategorizationFragment
                    }
                    ...PropertySpaceDetailsFragment
                    ...PropertySearchLinkFragment
                    ...PropertyUnitListViewFragment
                    ...LoyaltyDiscountToggleFragment
                    ...LegalDisclaimerFragment
                    ...HighlightedBenefitsFragment
                    ...LodgingOneKeyBurnSwitchFragment
                    ...UnitsCarouselFragment
                }
                travelerSelector(
                  context: $context
                  searchCriteria: $searchCriteria
                  propertyId: $propertyId
                ) {
                    unitGroups {
                    ...UnitGroupFragment
                    }
                    submitButton {
                    ...LodgingEGDSButtonFragment
                    }
                }
              }


              fragment PropertyUnitCategorizationFragment on LodgingCategorizedUnit {
                header {
                    text
                }
                features {
                    text
                }
                primarySelections {
                    propertyUnit {
                        id
                        ratePlans {
                            id
                            amenities {
                                description
                            }
                            paymentPolicy {
                              price {
                                displayMessages {
                                    lineItems {
                                      ...PriceMessageFragment
                                      ...EnrichedMessageFragment
                                    }
                                }
                              }
                            }
                          ...UnitCategorizationRatePlanFragment
                        }
                    }
                }
              }
            
              fragment UnitCategorizationRatePlanFragment on RatePlan {
                shareUrl {
                  value
                  link {
                    clientSideAnalytics {
                      linkName
                      referrerId
                    }
                    uri {
                      relativePath
                    }
                  }

                }
              }
              
              fragment PriceMessageFragment on DisplayPrice {
                price {
                  formatted
                }
              }
              
              fragment EnrichedMessageFragment on LodgingEnrichedMessage {
                value
              }
              





              
              fragment PropertyLevelOffersMessageFragment on OfferDetails {
                propertyLevelOffersMessage {
                  ...MessageResultFragment
                  ...SparkleBannerFragment
                  __typename
                }
                __typename
              }
              
              fragment MessageResultFragment on MessageResult {
                title {
                  text
                  ...MessagingResultTitleMediaFragment
                  __typename
                }
                subtitle {
                  text
                  ...MessagingResultTitleMediaFragment
                  __typename
                }
                action {
                  primary {
                    ...MessageActionContent
                    __typename
                  }
                  secondary {
                    ...MessageActionContent
                    __typename
                  }
                  __typename
                }
                type
                footerText
                __typename
              }
              
              fragment MessageActionContent on MessagingAction {
                text
                linkUrl
                referrerId
                actionDetails {
                  action
                  accessibilityLabel
                  __typename
                }
                analytics {
                  linkName
                  referrerId
                  __typename
                }
                __typename
              }
              
              fragment MessagingResultTitleMediaFragment on MessagingResultTitle {
                icon {
                  id
                  description
                  size
                  spotLight
                  __typename
                }
                illustration {
                  assetUri {
                    value
                    __typename
                  }
                  description
                  __typename
                }
                egdsMark {
                  id
                  description
                  url {
                    ... on HttpURI {
                      value
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment SparkleBannerFragment on MessageResult {
                __typename
                title {
                  ...MessageTitleFields
                  ...MessagingResultTitleMediaFragment
                  __typename
                }
                subtitle {
                  ...MessageTitleFields
                  __typename
                }
                action {
                  primary {
                    ...MessagingActionFields
                    __typename
                  }
                  secondary {
                    ...MessagingActionFields
                    __typename
                  }
                  __typename
                }
                type
              }
              
              fragment MessageTitleFields on MessagingResultTitle {
                text
                icon {
                  description
                  id
                  __typename
                }
                illustration {
                  assetUri {
                    value
                    __typename
                  }
                  description
                  __typename
                }
                __typename
              }
              
              fragment MessagingActionFields on MessagingAction {
                actionDetails {
                  action
                  accessibilityLabel
                  __typename
                }
                analytics {
                  linkName
                  referrerId
                  __typename
                }
                linkUrl
                referrerId
                text
                __typename
              }
              
              fragment PropertyLevelOffersCardFragment on OfferDetails {
                propertyLevelOffersCard {
                  ...EGDSStandardMessagingCardFragment
                  __typename
                }
                __typename
              }
              
              fragment EGDSStandardMessagingCardFragment on EGDSStandardMessagingCard {
                message
                heading
                background
                rightIcon {
                  __typename
                }
                graphic {
                  ...UIGraphicFragment
                  __typename
                }
                links {
                  ...EGDSStandardLinkFragment
                  __typename
                }
                buttons {
                  ...LodgingEGDSButtonFragment
                  __typename
                }
                __typename
              }

              fragment UIGraphicFragment on UIGraphic {
                ... on Icon {
                  __typename
                }
                ... on Mark {
                  ...MarkFragment
                  __typename
                }
                ... on Illustration {
                  ...IllustrationFragment
                  __typename
                }
                __typename
              }
              
              fragment MarkFragment on Mark {
                description
                id
                markSize: size
                url {
                  ... on HttpURI {
                    relativePath
                    value
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment IllustrationFragment on Illustration {
                description
                id
                link: url
                __typename
              }
              
              fragment EGDSStandardLinkFragment on EGDSStandardLink {
                action {
                  accessibility
                  analytics {
                    linkName
                    referrerId
                    __typename
                  }
                  resource {
                    value
                    ... on HttpURI {
                      relativePath
                      __typename
                    }
                    __typename
                  }
                  target
                  useRelativePath
                  __typename
                }
                disabled
                standardLinkIcon: icon {
                  __typename
                }
                iconPosition
                size
                text
                __typename
              }
              
              fragment LodgingEGDSButtonFragment on EGDSButton {
                accessibility
                analytics {
                  ...ClientSideAnalyticsFragment
                  __typename
                }
                action {
                  ...LodgingUILinkActionFragment
                  __typename
                }
                icon {
                  ...LodgingEGDSIconFragment
                  __typename
                }
                ... on UIPrimaryButton {
                  egdsElementId
                  __typename
                }
                disabled
                primary
                __typename
              }
              
              fragment ClientSideAnalyticsFragment on ClientSideAnalytics {
                eventType
                linkName
                referrerId
                uisPrimeMessages {
                  schemaName
                  messageContent
                  __typename
                }
                __typename
              }
              
              fragment LodgingUILinkActionFragment on UILinkAction {
                resource {
                  ... on HttpURI {
                    relativePath
                    value
                    __typename
                  }
                  __typename
                }
                target
                useRelativePath
                __typename
              }
              
              fragment LodgingEGDSIconFragment on Icon {
                description
                id
                size
                theme
                __typename
              }
            
              fragment SingleOfferFragment on OfferDetails {
                ...PropertySingleRatePlanFragment
                __typename
              }
              
              fragment PropertySingleRatePlanFragment on OfferDetails {
                errorMessage {
                  ...ErrorMessageFragment
                  __typename
                }
                alternateAvailabilityMsg {
                  ...AlternateAvailabilityMsgFragment
                  __typename
                }
                ...AlternateDatesFragment
                singleUnitOffer {
                  accessibilityLabel
                  ...TotalPriceFragment
                  ...PropertySingleOfferDetailsFragment
                  ratePlans {
                    priceDetails {
                      ...PricePresentationDialogFragment
                      __typename
                    }
                    marketingSection {
                      ...MarketingSectionFragment
                      __typename
                    }
                    shareUrl {
                      accessibilityLabel
                      value
                      link {
                        clientSideAnalytics {
                          linkName
                          referrerId
                          __typename
                        }
                        uri {
                          relativePath
                          value
                          __typename
                        }
                        __typename
                      }
                      __typename
                    }
                    ...ReservePropertyFragment
                    __typename
                  }
                  view
                  __typename
                }
                ...PropertySingleOfferDialogLinkFragment
                __typename
              }
              
              fragment ErrorMessageFragment on MessageResult {
                title {
                  text
                  __typename
                }
                action {
                  primary {
                    text
                    linkUrl
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment AlternateAvailabilityMsgFragment on LodgingComplexLinkMessage {
                text {
                  value
                  __typename
                }
                actionLink {
                  link {
                    uri {
                      relativePath
                      value
                      __typename
                    }
                    __typename
                  }
                  value
                  __typename
                }
                __typename
              }
              
              fragment AlternateDatesFragment on OfferDetails {
                alternateDates {
                  header {
                    text
                    subText
                    __typename
                  }
                  options {
                    ...AlternateDateOptionFragment
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment AlternateDateOptionFragment on AlternateDateOption {
                dates {
                  link {
                    uri {
                      relativePath
                      __typename
                    }
                    referrerId
                    __typename
                  }
                  value
                  __typename
                }
                price {
                  displayPrice {
                    formatted
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment TotalPriceFragment on SingleUnitOfferDetails {
                totalPrice {
                  label {
                    __typename
                  }
                  amount {
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PropertySingleOfferDetailsFragment on SingleUnitOfferDetails {
                id
                ...PriceMessagesFragment
                ratePlans {
                  ...SingleUnitPriceSummaryFragment
                  ...HighlightedMessagesFragment
                  __typename
                }
                ...SingleOfferAvailabilityCtaFragment
                __typename
              }
              
              fragment PriceMessagesFragment on SingleUnitOfferDetails {
                displayPrice {
                  priceMessages {
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment SingleUnitPriceSummaryFragment on RatePlan {
                ...PropertyOffersPriceChangeMessageFragment
                priceDetails {
                  pointsApplied
                  price {
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PropertyOffersPriceChangeMessageFragment on RatePlan {
                headerMessage {
                  __typename
                }
                __typename
              }
              
              fragment HighlightedMessagesFragment on RatePlan {
                id
                highlightedMessages {
                  ...LodgingPlainDialogFragment
                  ...LodgingPlainMessageFragment
                  ...LodgingPolicyDialogFragment
                  __typename
                }
                __typename
              }
              
              fragment LodgingPlainDialogFragment on LodgingPlainDialog {
                content
                primaryUIButton {
                  __typename
                }
                secondaryUIButton {
                  ...UISecondaryButtonFragment
                  __typename
                }
                primaryButton {
                  text
                  analytics {
                    linkName
                    referrerId
                    __typename
                  }
                  __typename
                }
                trigger {
                  clientSideAnalytics {
                    linkName
                    referrerId
                    __typename
                  }
                  icon {
                    description
                    id
                    __typename
                  }
                  theme
                  value
                  secondaryValue
                  __typename
                }
                __typename
              }
              
              fragment UISecondaryButtonFragment on UISecondaryButton {
                primary
                action {
                  __typename
                  analytics {
                    referrerId
                    linkName
                    __typename
                  }
                  ... on UILinkAction {
                    resource {
                      value
                      __typename
                    }
                    __typename
                  }
                }
                __typename
              }
              
              fragment LodgingPolicyDialogFragment on LodgingPolicyDialog {
                policyContent {
                  ...LodgingDetailedTimelineFragment
                  __typename
                }
                trigger {
                  clientSideAnalytics {
                    linkName
                    referrerId
                    __typename
                  }
                  icon {
                    description
                    id
                    __typename
                  }
                  theme
                  value
                  secondaryValue
                  accessibilityLabel
                  __typename
                }
                toolbar {
                  title
                  icon {
                    description
                    __typename
                  }
                  clientSideAnalytics {
                    linkName
                    referrerId
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment LodgingDetailedTimelineFragment on LodgingDetailedTimeline {
                title {
                  ... on EGDSStylizedText {
                    text
                    __typename
                  }
                  ... on EGDSGraphicText {
                    text
                    __typename
                  }
                  __typename
                }
                policies {
                  ...LodgingPolicyFragment
                  __typename
                }
                __typename
              }
              
              fragment LodgingPolicyFragment on LodgingPolicy {
                title {
                  text
                  __typename
                }
                ...MessageSectionsFragment
                clientSideAnalytics {
                  linkName
                  referrerId
                  __typename
                }
                __typename
              }
              
              fragment MessageSectionsFragment on LodgingPolicy {
                messageSections {
                  primaryInfo {
                    ...PrimaryInfoFragment
                    __typename
                  }
                  secondaryInfo {
                    ...SecondaryInfoFragment
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PrimaryInfoFragment on PrimaryInfo {
                heading {
                  text
                  __typename
                }
                subheading {
                  text
                  __typename
                }
                __typename
              }
              
              fragment SecondaryInfoFragment on SecondaryInfo {
                title {
                  text
                  __typename
                }
                message
                __typename
              }
              
              fragment LodgingPlainMessageFragment on LodgingPlainMessage {
                value
                __typename
              }
              
              fragment SingleOfferAvailabilityCtaFragment on SingleUnitOfferDetails {
                availabilityCallToAction {
                  ... on LodgingPlainMessage {
                    value
                    __typename
                  }
                  ... on LodgingButton {
                    text
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment MarketingSectionFragment on MarketingSection {
                title {
                  text
                  __typename
                }
                feeDialog {
                  title
                  content
                  tertiaryUIButton {
                    primary
                    __typename
                  }
                  trigger {
                    value
                    mark {
                      id
                      __typename
                    }
                    icon {
                      id
                      size
                      __typename
                    }
                    clientSideAnalytics {
                      linkName
                      referrerId
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                paymentDetails {
                  __typename
                }
                __typename
              }
              
              fragment ReservePropertyFragment on RatePlan {
                paymentPolicy {
                  paymentType
                  heading
                  descriptions {
                    heading
                    header {
                      text
                      mark {
                        id
                        token
                        url {
                          value
                          __typename
                        }
                        __typename
                      }
                      __typename
                    }
                    items {
                      icon {
                        id
                        token
                        __typename
                      }
                      __typename
                    }
                    __typename
                  }
                  price {
                    __typename
                  }
                  __typename
                }
                priceDetails {
                  __typename
                }
                ...PropertyReserveButtonFragment
                __typename
              }
              
              fragment PropertyReserveButtonFragment on RatePlan {
                id
                reserveCallToAction {
                  __typename
                  ... on EtpDialog {
                    content {
                      ... on LodgingPaymentOptionsDialogContent {
                        messages {
                          ...LodgingEGDSGraphicTextFragment
                          __typename
                        }
                        __typename
                      }
                      __typename
                    }
                    trigger {
                      value
                      accessibilityLabel
                      __typename
                    }
                    toolbar {
                      icon {
                        description
                        __typename
                      }
                      title
                      __typename
                    }
                    __typename
                  }
                  ... on LodgingForm {
                    ...PropertyLodgingFormButtonFragment
                    __typename
                  }
                  ... on LodgingMemberSignInDialog {
                    ...LodgingMemberSignInDialogFragment
                    __typename
                  }
                  ... on DatelessCheckAvailability {
                    ...DatelessCheckAvailabilityFragment
                    __typename
                  }
                }
                etpDialogTopMessage {
                  ...MessageResultFragment
                  __typename
                }
                priceDetails {
                  action {
                    ... on SelectPackageActionInput {
                      packageOfferId
                      __typename
                    }
                    __typename
                  }
                  price {
                    multiItemPriceToken
                    __typename
                  }
                  hotelCollect
                  propertyNaturalKeys {
                    id
                    businessModelType
                    checkIn {
                      month
                      day
                      year
                      __typename
                    }
                    checkOut {
                      month
                      day
                      year
                      __typename
                    }
                    inventoryType
                    noCreditCard
                    petsIncluded
                    ratePlanId
                    roomTypeId
                    ratePlanType
                    rooms {
                      childAges
                      numberOfAdults
                      __typename
                    }
                    shoppingPath
                    __typename
                  }
                  noCreditCard
                  paymentModel
                  ...PropertyPaymentOptionsFragment
                  dealMarker
                  __typename
                }
                __typename
              }
              
              fragment LodgingEGDSGraphicTextFragment on EGDSGraphicText {
                text
                graphic {
                  ... on Icon {
                    id
                    size
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PropertyLodgingFormButtonFragment on LodgingForm {
                action
                inputs {
                  ... on LodgingTextInput {
                    name
                    type
                    value
                    __typename
                  }
                  __typename
                }
                method
                submit {
                  text
                  accessibilityLabel
                  analytics {
                    linkName
                    referrerId
                    __typename
                  }
                  lodgingRecommendationClickstreamEvent {
                    recommendationResponseId
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PropertyPaymentOptionsFragment on Offer {
                loyaltyMessage {
                  ... on LodgingEnrichedMessage {
                    value
                    state
                    __typename
                  }
                  __typename
                }
                lodgingPrepareCheckout {
                  ...PropertyLodgingPrepareCheckoutFragment
                  __typename
                }
                offerBookButton {
                  ...PropertyLodgingFormButtonFragment
                  __typename
                }
                ...PropertyPaymentPriceFragment
                price {
                  __typename
                }
                __typename
              }
              
              fragment PropertyLodgingPrepareCheckoutFragment on LodgingPrepareCheckout {
                checkoutButton {
                  __typename
                }
                action {
                  checkoutOptions {
                    type
                    value
                    __typename
                  }
                  offerTokens {
                    token
                    lineOfBusiness
                    __typename
                  }
                  totalPrice {
                    amount
                    currencyInfo {
                      code
                      __typename
                    }
                    __typename
                  }
                  analytics {
                    eventType
                    linkName
                    referrerId
                    __typename
                  }
                  analyticsList {
                    eventType
                    linkName
                    referrerId
                    __typename
                  }
                  lodgingRecommendationClickstreamEvent {
                    recommendationResponseId
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PropertyPaymentPriceFragment on Offer {
                price {
                  lead {
                    formatted
                    __typename
                  }
                  priceMessaging {
                    value
                    theme
                    __typename
                  }
                  options {
                    leadingCaption
                    displayPrice {
                      formatted
                      __typename
                    }
                    disclaimer {
                      value
                      __typename
                    }
                    priceDisclaimer {
                      content
                      primaryButton {
                        text
                        __typename
                      }
                      trigger {
                        icon {
                          description
                          __typename
                        }
                        __typename
                      }
                      __typename
                    }
                    accessibilityLabel
                    strikeOut {
                      formatted
                      __typename
                    }
                    loyaltyPrice {
                      unit
                      amount {
                        formatted
                        __typename
                      }
                      totalStrikeOutPoints {
                        formatted
                        __typename
                      }
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment LodgingMemberSignInDialogFragment on LodgingMemberSignInDialog {
                dialogTrigger: trigger {
                  value
                  accessibilityLabel
                  __typename
                }
                title
                dialogContent {
                  ...EGDSParagraphFragment
                  __typename
                }
                actionDialog {
                  ...EGDSActionDialogFragment
                  __typename
                }
                __typename
              }
              
              fragment EGDSParagraphFragment on EGDSParagraph {
                text
                style
                __typename
              }
              
              fragment EGDSActionDialogFragment on EGDSActionDialog {
                closeAnalytics {
                  referrerId
                  linkName
                  __typename
                }
                footer {
                  ... on EGDSStackedDialogFooter {
                    buttons {
                      ... on EGDSOverlayButton {
                        __typename
                      }
                      __typename
                    }
                    __typename
                  }
                  ... on EGDSInlineDialogFooter {
                    buttons {
                      ... on UIPrimaryButton {
                        accessibility
                        primary
                        disabled
                        action {
                          ... on UILinkAction {
                            resource {
                              value
                              __typename
                            }
                            analytics {
                              linkName
                              referrerId
                              __typename
                            }
                            __typename
                          }
                          __typename
                        }
                        __typename
                      }
                      ... on UITertiaryButton {
                        accessibility
                        primary
                        action {
                          ... on UILinkAction {
                            resource {
                              value
                              __typename
                            }
                            __typename
                          }
                          __typename
                        }
                        disabled
                        __typename
                      }
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment DatelessCheckAvailabilityFragment on DatelessCheckAvailability {
                checkAvailabilityButton {
                  ...LodgingEGDSButtonFragment
                  __typename
                }
                __typename
              }
              
              fragment PropertySingleOfferDialogLinkFragment on OfferDetails {
                singleUnitOfferDialog {
                  content {
                    ...PropertySingleOfferDetailsFragment
                    ratePlans {
                      priceDetails {
                        ...PricePresentationDialogFragment
                        __typename
                      }
                      marketingSection {
                        ...MarketingSectionFragment
                        __typename
                      }
                      ...ReservePropertyFragment
                      __typename
                    }
                    __typename
                  }
                  trigger {
                    value
                    secondaryValue
                    __typename
                  }
                  toolbar {
                    icon {
                      description
                      __typename
                    }
                    title
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PricePresentationDialogFragment on Offer {
                pricePresentationDialog {
                  toolbar {
                    title
                    icon {
                      __typename
                    }
                    clientSideAnalytics {
                      linkName
                      referrerId
                      __typename
                    }
                    __typename
                  }
                  trigger {
                    clientSideAnalytics {
                      linkName
                      referrerId
                      __typename
                    }
                    accessibilityLabel
                    value
                    __typename
                  }
                  __typename
                }
                pricePresentation {
                  title {
                    primary
                    __typename
                  }
                  sections {
                    ...PricePresentationSectionFragment
                    __typename
                  }
                  footer {
                    header
                    messages {
                      ...PriceLineElementFragment
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PricePresentationSectionFragment on PricePresentationSection {
                header {
                  name {
                    ...PricePresentationLineItemEntryFragment
                    __typename
                  }
                  enrichedValue {
                    ...PricePresentationLineItemEntryFragment
                    __typename
                  }
                  __typename
                }
                subSections {
                  ...PricePresentationSubSectionFragment
                  __typename
                }
                __typename
              }
              
              fragment PricePresentationSubSectionFragment on PricePresentationSubSection {
                header {
                  name {
                    primaryMessage {
                      __typename
                      ... on PriceLineText {
                        primary
                        __typename
                      }
                      ... on PriceLineHeading {
                        primary
                        __typename
                      }
                    }
                    __typename
                  }
                  enrichedValue {
                    ...PricePresentationLineItemEntryFragment
                    __typename
                  }
                  __typename
                }
                items {
                  ...PricePresentationLineItemFragment
                  __typename
                }
                __typename
              }
              
              fragment PricePresentationLineItemFragment on PricePresentationLineItem {
                enrichedValue {
                  ...PricePresentationLineItemEntryFragment
                  __typename
                }
                name {
                  ...PricePresentationLineItemEntryFragment
                  __typename
                }
                __typename
              }
              
              fragment PricePresentationLineItemEntryFragment on PricePresentationLineItemEntry {
                primaryMessage {
                  ...PriceLineElementFragment
                  __typename
                }
                secondaryMessages {
                  ...PriceLineElementFragment
                  __typename
                }
                __typename
              }
              
              fragment PriceLineElementFragment on PricePresentationLineItemMessage {
                __typename
                ...PriceLineTextFragment
                ...PriceLineHeadingFragment
                ...PriceLineBadgeFragment
                ...InlinePriceLineTextFragment
              }
              
              fragment PriceLineTextFragment on PriceLineText {
                __typename
                theme
                primary
                weight
                additionalInfo {
                  ...AdditionalInformationPopoverFragment
                  __typename
                }
                icon {
                  __typename
                }
                graphic {
                  ...UIGraphicFragment
                  __typename
                }
              }
              
              fragment AdditionalInformationPopoverFragment on AdditionalInformationPopover {
                icon {
                  __typename
                }
                enrichedSecondaries {
                  ...AdditionalInformationPopoverSectionFragment
                  __typename
                }
                analytics {
                  linkName
                  referrerId
                  __typename
                }
                __typename
              }
              
              fragment AdditionalInformationPopoverSectionFragment on AdditionalInformationPopoverSection {
                __typename
                ... on AdditionalInformationPopoverTextSection {
                  ...AdditionalInformationPopoverTextSectionFragment
                  __typename
                }
                ... on AdditionalInformationPopoverListSection {
                  ...AdditionalInformationPopoverListSectionFragment
                  __typename
                }
                ... on AdditionalInformationPopoverGridSection {
                  ...AdditionalInformationPopoverGridSectionFragment
                  __typename
                }
              }
              
              fragment AdditionalInformationPopoverTextSectionFragment on AdditionalInformationPopoverTextSection {
                __typename
                text {
                  __typename
                  text
                  ... on EGDSStandardLink {
                    standardLinkIcon: icon {
                      __typename
                    }
                    action {
                      accessibility
                      analytics {
                        linkName
                        referrerId
                        __typename
                      }
                      resource {
                        value
                        __typename
                      }
                      target
                      __typename
                    }
                    iconPosition
                    size
                    text
                    __typename
                  }
                }
              }
              
              fragment AdditionalInformationPopoverListSectionFragment on AdditionalInformationPopoverListSection {
                __typename
                content {
                  __typename
                  items {
                    text
                    __typename
                  }
                }
              }
              
              fragment AdditionalInformationPopoverGridSectionFragment on AdditionalInformationPopoverGridSection {
                __typename
                subSections {
                  header {
                    name {
                      primaryMessage {
                        ...AdditionalInformationPopoverGridLineItemMessageFragment
                        __typename
                      }
                      __typename
                    }
                    __typename
                  }
                  items {
                    name {
                      ...AdditionalInformationPopoverGridLineItemEntryFragment
                      __typename
                    }
                    enrichedValue {
                      ...AdditionalInformationPopoverGridLineItemEntryFragment
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
              }
              
              fragment AdditionalInformationPopoverGridLineItemEntryFragment on PricePresentationLineItemEntry {
                primaryMessage {
                  ...AdditionalInformationPopoverGridLineItemMessageFragment
                  __typename
                }
                secondaryMessages {
                  ...AdditionalInformationPopoverGridLineItemMessageFragment
                  __typename
                }
                __typename
              }
              
              fragment AdditionalInformationPopoverGridLineItemMessageFragment on PricePresentationLineItemMessage {
                ... on PriceLineText {
                  __typename
                  primary
                }
                ... on PriceLineHeading {
                  __typename
                  tag
                  size
                  primary
                }
                __typename
              }
              
              fragment PriceLineHeadingFragment on PriceLineHeading {
                __typename
                primary
                tag
                size
                additionalInfo {
                  ...AdditionalInformationPopoverFragment
                  __typename
                }
                icon {
                  __typename
                }
              }
              
              fragment PriceLineBadgeFragment on PriceLineBadge {
                __typename
                badge {
                  accessibility
                  text
                  theme
                  __typename
                }
              }
              
              fragment InlinePriceLineTextFragment on InlinePriceLineText {
                __typename
                inlineItems {
                  ...PriceLineTextFragment
                  __typename
                }
              }
              
              fragment PropertyStickyBookBarFragment on OfferDetails {
                soldOut
                stickyBar {
                  ...StickyPriceSummaryFragment
                  ...PropertyStickyBookBarPricePresentationDialogFragment
                  structuredData {
                    itemprop
                    itemscope
                    itemtype
                    content
                    __typename
                  }
                  __typename
                }
                ...StickyCtaFragment
                ...PropertySingleOfferDialogLinkFragment
                __typename
              }
              
              fragment StickyCtaFragment on OfferDetails {
                stickyBar {
                  stickyButton {
                    text
                    targetRef
                    __typename
                  }
                  __typename
                }
                shoppingContext {
                  multiItem {
                    packageType
                    __typename
                  }
                  __typename
                }
                ...PropertySingleOfferDialogLinkFragment
                __typename
              }
              
              fragment StickyPriceSummaryFragment on PropertyDetailsStickyBar {
                qualifier
                subText
                price {
                  formattedDisplayPrice
                  accessibilityLabel
                  priceDisclaimer {
                    content
                    primaryUIButton {
                      primary
                      __typename
                    }
                    trigger {
                      icon {
                        id
                        size
                        __typename
                      }
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                propertyPrice {
                  __typename
                }
                __typename
              }
              
              fragment PropertyStickyBookBarPricePresentationDialogFragment on PropertyDetailsStickyBar {
                pricePresentationDialog {
                  toolbar {
                    title
                    icon {
                      __typename
                    }
                    clientSideAnalytics {
                      linkName
                      referrerId
                      __typename
                    }
                    __typename
                  }
                  trigger {
                    clientSideAnalytics {
                      linkName
                      referrerId
                      __typename
                    }
                    value
                    __typename
                  }
                  __typename
                }
                pricePresentation {
                  title {
                    primary
                    __typename
                  }
                  sections {
                    ...PricePresentationSectionFragment
                    __typename
                  }
                  footer {
                    header
                    messages {
                      ...PriceLineElementFragment
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PropertyStickyPriceHeaderFragment on OfferDetails {
                soldOut
                stickyBar {
                  ...StickyPriceSummaryFragment
                  __typename
                }
                ...StickyCtaFragment
                __typename
              }
              
              fragment PropertyUnitFragment on PropertyUnit {
                __typename
                ... on PropertyUnit {
                  id
                  header {
                    ...PropertyOffersHeaderFragment
                    __typename
                  }
                  features {
                    ...PropertyFeaturesFragment
                    __typename
                  }
                  unitGallery {
                    accessibilityLabel
                    images {
                      image {
                        description
                        url
                        __typename
                      }
                      __typename
                    }
                    __typename
                  }
                  ratePlans {
                    __typename
                  }
                  ratePlansExpando {
                    ...RatePlansExpandoFragment
                    __typename
                  }
                  roomAmenities {
                    __typename
                  }
                  detailsDialog {
                    toolbar {
                      clientSideAnalytics {
                        referrerId
                        linkName
                        __typename
                      }
                      __typename
                    }
                    trigger {
                      clientSideAnalytics {
                        referrerId
                        linkName
                        __typename
                      }
                      __typename
                    }
                    __typename
                  }
                  spaceDetails {
                    __typename
                  }
                  ...SleepingArrangementsFragment
                  __typename
                }
              }
              
              fragment PropertyOffersHeaderFragment on LodgingHeader {
                text
                subText
                __typename
              }
              
              fragment PropertyFeaturesFragment on PropertyInfoItem {
                text
                graphic {
                  __typename
                  ... on Icon {
                    description
                    id
                    __typename
                  }
                  ... on Mark {
                    description
                    id
                    url {
                      value
                      __typename
                    }
                    __typename
                  }
                }
                moreInfoDialog {
                  ...LodgingPlainSheetFragment
                  __typename
                }
                __typename
              }
              
              fragment LodgingPlainSheetFragment on LodgingPlainDialog {
                __typename
                content
                trigger {
                  clientSideAnalytics {
                    linkName
                    referrerId
                    __typename
                  }
                  icon {
                    description
                    id
                    __typename
                  }
                  theme
                  value
                  __typename
                }
                toolbar {
                  title
                  icon {
                    description
                    __typename
                  }
                  clientSideAnalytics {
                    linkName
                    referrerId
                    __typename
                  }
                  __typename
                }
              }


              fragment RatePlansExpandoFragment on RatePlansExpando {
                collapseButton {
                  text
                  __typename
                }
                expandButton {
                  text
                  __typename
                }
                __typename
              }
              
              fragment SleepingArrangementsFragment on PropertyUnit {
                spaceDetails {
                  __typename
                }
                __typename
              }

              fragment PropertySpaceDetailsFragment on OfferDetails {
                spaceDetails {
                  virtualTourPrompt {
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment PropertySearchLinkFragment on OfferDetails {
                propertySearchLink {
                  ...LodgingLinkMessageFragment
                  __typename
                }
                partnerPropertySearchLink {
                  ...LodgingLinkMessageFragment
                  __typename
                }
                __typename
              }
              
              fragment LodgingLinkMessageFragment on LodgingLinkMessage {
                icon {
                  id
                  __typename
                }
                link {
                  clientSideAnalytics {
                    referrerId
                    linkName
                    __typename
                  }
                  uri {
                    relativePath
                    value
                    __typename
                  }
                  referrerId
                  __typename
                }
                value
                __typename
              }
              
              fragment PropertyUnitListViewFragment on OfferDetails {
                categorizedListings {
                  ...PropertyUnitListViewElementFragment
                  __typename
                }
                __typename
              }
              
              fragment PropertyUnitListViewElementFragment on LodgingCategorizedUnit {
                header {
                  text
                  __typename
                }
                __typename
              }
              
              fragment LoyaltyDiscountToggleFragment on OfferDetails {
                loyaltyDiscount {
                  saveWithPointsMessage
                  saveWithPointsActionMessage
                  __typename
                }
                __typename
              }
              
              fragment LegalDisclaimerFragment on OfferDetails {
                legalDisclaimer {
                  content {
                    markupType
                    text
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment HighlightedBenefitsFragment on OfferDetails {
                highlightedBenefits {
                  listItems {
                    icon {
                      id
                      __typename
                    }
                    impressionAnalytics {
                      referrerId
                      __typename
                    }
                    text
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment LodgingOneKeyBurnSwitchFragment on OfferDetails {
                lodgingOneKeyBurnSwitch {
                  burnSwitchGraphic: graphic {
                    ...UIGraphicFragment
                    __typename
                  }
                  switch {
                    enabled
                    checked
                    checkedLabel
                    uncheckedLabel
                    checkedDescription
                    uncheckedDescription
                    checkedAnalytics {
                      linkName
                      referrerId
                      __typename
                    }
                    uncheckedAnalytics {
                      linkName
                      referrerId
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment UnitGroupFragment on LodgingUnitGroup {
                inputs {
                  ...LodgingGuestGroupFragment
                  ...LodgingPetGroupFragment
                  __typename
                }
                __typename
              }
              
              fragment LodgingGuestGroupFragment on LodgingGuestGroup {
                adults {
                  primary {
                    ... on EGDSBasicStepInput {
                      min
                      max
                      label
                      step
                      value
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                children {
                  primary {
                    ... on EGDSBasicStepInput {
                      min
                      max
                      label
                      step
                      subLabel
                      value
                      __typename
                    }
                    __typename
                  }
                  secondary {
                    title {
                      value
                      icon {
                        theme
                        description
                        token
                        __typename
                      }
                      __typename
                    }
                    inputs {
                      ... on EGDSBasicSelect {
                        egdsElementId
                        label
                        options {
                          label
                          selected
                          value
                          __typename
                        }
                        __typename
                      }
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                maxGuest
                maxChildren
                __typename
              }
              
              fragment LodgingPetGroupFragment on LodgingPetGroup {
                input {
                  primary {
                    ... on EGDSBasicCheckBox {
                      description
                      enabled
                      errorMessage
                      name
                      required
                      label {
                        text
                        __typename
                      }
                      state
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment UnitsCarouselFragment on OfferDetails {
                unitsCarousel {
                  carousel {
                    nextButton {
                      __typename
                      icon {
                        description
                        __typename
                      }
                    }
                    previousButton {
                      __typename
                      icon {
                        description
                        __typename
                      }
                    }
                    __typename
                  }
                  heading {
                    ... on EGDSStylizedText {
                      text
                      __typename
                    }
                    __typename
                  }
                  impressionAnalytics {
                    linkName
                    referrerId
                    __typename
                  }
                  items {
                    ... on LodgingUnitCard {
                      ...LodgingUnitCardFragment
                      __typename
                    }
                    __typename
                  }
                  __typename
                }
                __typename
              }
              
              fragment LodgingUnitCardFragment on LodgingUnitCard {
                unitId
                header {
                  text
                  __typename
                }
                image {
                  description
                  url
                  __typename
                }
                trigger {
                  accessibilityLabel
                  clientSideAnalytics {
                    linkName
                    referrerId
                    __typename
                  }
                  secondaryValue
                  theme
                  value
                  __typename
                }
                __typename
              }
              ',
            "variables" => [
                "propertyId"=> "3957818",
                "searchCriteria"=> [
                    "primary"=> [
                        "dateRange"=> [
                            "checkInDate"=> [
                                "day"=> 12,
                                "month"=> 6,
                                "year"=> 2023
                            ],
                            "checkOutDate"=> [
                                "day"=> 13,
                                "month"=> 6,
                                "year"=> 2023
                            ]
                        ],
                        "destination"=> [
                            "regionName"=> "Ilhéus, Bahia (estado), Brasil",
                            "regionId"=> "1615",
                            "coordinates"=> [
                                "latitude"=> -14.816838,
                                "longitude"=> -39.025248
                            ],
                            "pinnedPropertyId"=> "3957818",
                            "propertyIds"=> null,
                            "mapBounds"=> null
                        ],
                        "rooms"=> [
                            [
                                "adults"=> 2,
                                "children"=> []
                            ]
                        ]
                    ],
                    "secondary"=> [
                        "counts"=> [],
                        "booleans"=> [],
                        "selections"=> [
                            [
                                "id"=> "sort",
                                "value"=> "RECOMMENDED"
                            ],
                            [
                                "id"=> "privacyTrackingState",
                                "value"=> "CAN_NOT_TRACK"
                            ],
                            [
                                "id"=> "useRewards",
                                "value"=> "SHOP_WITHOUT_POINTS"
                            ]
                        ],
                        "ranges"=> []
                    ]
                ],
                "shoppingContext"=> [
                    "multiItem"=> null
                ],
                "travelAdTrackingInfo" => null,
                "searchOffer"=> [
                    "offerPrice"=> [
                        "offerTimestamp" => "1685017471604",
                        "price"=> [
                            "amount"=> 250,
                            "currency"=> "BRL"
                        ]
                    ],
                    "roomTypeId"=> "32709",
                    "ratePlanId"=> "108875675",
                    "offerDetails" => []
                ],
                "referrer"=> "HSR",
                "context"=> [
                    "siteId"=> 301800003,
                    "locale"=> "pt_BR",
                    "eapid"=> 3,
                    "currency"=> "BRL",
                    "device"=> [
                        "type"=> "DESKTOP"
                    ],
                    "identity"=> [
                        "duaid"=> "b895f215-3bf5-4fbc-971f-dc46a6e3cc6b",
                        "expUserId"=> "-1",
                        "tuid"=> "-1",
                        "authState"=> "ANONYMOUS"
                    ],
                    "privacyTrackingState"=> "CAN_TRACK",
                    "debugContext"=> [
                        "abacusOverrides"=> [],
                        "alterMode"=> "RELEASED"
                    ]
                ]
                
            ] 
        ];


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.hoteis.com/graphql');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($query));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Authority: www.hoteis.com';
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.5';
        $headers[] = 'Client-Info: shopping-pwa,07da2d4e6bdc94b0df448dcf1386c2827c84a3c7,us-west-2';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Origin: https://www.hoteis.com';
        $headers[] = 'Referer: https://www.hoteis.com';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36';
        $headers[] = 'X-Page-Id: page.Hotels.Infosite.Information,H,30';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        dump($result);
    }
}
