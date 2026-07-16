import { usePage } from '@inertiajs/react';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import { cn } from '@/lib/utils';

export function BookTitlePreview({ title, coverUrl, className }) {
    const { branding } = usePage().props;
    const resolvedTitle = title || 'Untitled';
    const resolvedCover = coverUrl || branding?.assets?.defaultBook || '';

    return (
        <Tooltip>
            <TooltipTrigger asChild>
                <span
                    tabIndex={0}
                    className={cn(
                        'block max-w-[220px] cursor-default truncate font-medium outline-none hover:text-primary focus-visible:text-primary',
                        className,
                    )}
                >
                    {resolvedTitle}
                </span>
            </TooltipTrigger>
            <TooltipContent
                side="bottom"
                align="start"
                sideOffset={8}
                className="w-[200px] overflow-hidden border bg-background p-0 text-foreground shadow-lg [&>svg]:hidden"
            >
                <img
                    src={resolvedCover}
                    alt=""
                    className="mb-0 block h-[140px] w-full bg-muted object-cover"
                />
                <span className="block p-2.5 text-xs font-semibold leading-snug text-foreground">
                    {resolvedTitle}
                </span>
            </TooltipContent>
        </Tooltip>
    );
}
