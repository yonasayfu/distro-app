export type HandbookDocumentLink = {
    key: string;
    title: string;
    description: string;
    mode: string;
};

export type HandbookGroup = {
    key: string;
    title: string;
    description: string;
    documents: HandbookDocumentLink[];
};

export type HandbookLesson = {
    key: string;
    title: string;
    entryNumber: number | null;
    html?: string;
};

export type HandbookFilters = {
    document: string;
    lesson: string;
};

export type HandbookDocument = {
    key: string;
    title: string;
    description: string;
    html: string;
    mode: string;
    group: string;
};
